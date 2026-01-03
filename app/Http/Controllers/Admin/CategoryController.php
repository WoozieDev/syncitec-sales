<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Category::class);

        $search = trim((string) $request->get('search', ''));
        $parent = $request->get('parent'); // null|id
        $status = $request->get('status'); // null|active|inactive

        $categories = Category::query()
            ->with('parent:id,name')
            ->when($parent !== null && $parent !== '', fn ($q) => $q->where('parent_id', $parent))
            ->when($status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($search !== '', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            })
            ->withCount('children')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Category $c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'is_active' => (bool) $c->is_active,
                'sort_order' => (int) $c->sort_order,
                'parent' => $c->parent ? ['id' => $c->parent->id, 'name' => $c->parent->name] : null,
                'children_count' => (int) $c->children_count,
                'created_at' => $c->created_at?->toDateTimeString(),
                'deleted_at' => $c->deleted_at?->toDateTimeString(),
            ]);

        // Parents select (solo roots)
        $parents = Category::query()
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('admin/categories/Index', [
            'categories' => $categories,
            'parents' => $parents,
            'filters' => [
                'search' => $search,
                'parent' => $parent,
                'status' => $status,
            ],
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Category::class);

        $parents = Category::query()
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('admin/categories/Create', [
            'parents' => $parents,
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        Gate::authorize('create', Category::class);

        $data = $request->validated();

        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;

        $i = 2;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$i}";
            $i++;
        }

        Category::create([
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'slug' => $slug,
            'is_active' => (bool) $data['is_active'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function show(Category $category) { abort(501); }

    public function edit(Category $category): Response
    {
        Gate::authorize('update', $category);

        $parents = Category::query()
            ->whereNull('parent_id')
            ->where('id', '!=', $category->id) // evita ser padre de sí mismo
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('admin/categories/Edit', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'parent_id' => $category->parent_id,
                'is_active' => (bool) $category->is_active,
                'sort_order' => (int) $category->sort_order,
            ],
            'parents' => $parents,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        Gate::authorize('update', $category);

        $data = $request->validated();

        // Protección simple: evitar que una categoría sea su propio padre
        if (!empty($data['parent_id']) && (int) $data['parent_id'] === (int) $category->id) {
            return back()->with('error', 'Una categoría no puede ser padre de sí misma.');
        }

        $category->update([
            'name' => $data['name'],
            'parent_id' => $data['parent_id'] ?? null,
            'is_active' => (bool) $data['is_active'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);

        // MVP: slug NO cambia automáticamente (para no romper URLs)
        return redirect()
            ->route('admin.categories.edit', $category)
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category)
    {
        Gate::authorize('delete', $category);

        if ($category->children()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'No puedes eliminar una categoría que tiene subcategorías.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    public function restore(Category $category)
    {
        Gate::authorize('update', $category);

        $category->restore();

        return redirect()
            ->route('admin.categories.edit', $category)
            ->with('success', 'Categoría restaurada correctamente.');
    }


}

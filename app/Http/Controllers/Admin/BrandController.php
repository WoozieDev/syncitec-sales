<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BrandController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Brand::class);

        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status'); // active|inactive|null

        $brands = Brand::query()
            ->when($status === 'active', fn($q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn($q) => $q->where('is_active', false))
            ->when($search !== '', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Brand $b) => [
                'id' => $b->id,
                'name' => $b->name,
                'slug' => $b->slug,
                'is_active' => (bool) $b->is_active,
                'sort_order' => (int) $b->sort_order,
                'deleted_at' => $b->deleted_at?->toDateTimeString(),
            ]);

        return Inertia::render('admin/brands/Index', [
            'brands' => $brands,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Brand::class);

        return Inertia::render('admin/brands/Create');
    }

    public function store(StoreBrandRequest $request)
    {
        Gate::authorize('create', Brand::class);

        $data = $request->validated();

        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $i = 2;

        while (Brand::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$i}";
            $i++;
        }

        Brand::create([
            'name' => $data['name'],
            'slug' => $slug,
            'is_active' => (bool) $data['is_active'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Marca creada correctamente.');
    }

    public function show(Brand $brand)
    {
        abort(501); // MVP: no show
    }

    public function edit(Brand $brand): Response
    {
        Gate::authorize('update', $brand);

        return Inertia::render('admin/brands/Edit', [
            'brand' => [
                'id' => $brand->id,
                'name' => $brand->name,
                'slug' => $brand->slug,
                'is_active' => (bool) $brand->is_active,
                'sort_order' => (int) $brand->sort_order,
                'deleted_at' => $brand->deleted_at?->toDateTimeString(),
            ],
        ]);
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        Gate::authorize('update', $brand);

        $data = $request->validated();

        $brand->update([
            'name' => $data['name'],
            'is_active' => (bool) $data['is_active'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ]);

        // MVP: slug no cambia automáticamente
        return redirect()
            ->route('admin.brands.edit', $brand)
            ->with('success', 'Marca actualizada correctamente.');
    }

    public function destroy(Brand $brand)
    {
        Gate::authorize('delete', $brand);

        // En Products pondremos regla: si tiene productos, bloquear delete.
        $brand->delete();

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Marca eliminada correctamente.');
    }

    public function restore(Brand $brand)
    {
        Gate::authorize('update', $brand);

        $brand->restore();

        return redirect()
            ->route('admin.brands.edit', $brand)
            ->with('success', 'Marca restaurada correctamente.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Product::class);

        $search = trim((string) $request->get('search', ''));
        $category = $request->get('category'); // id|null
        $brand = $request->get('brand'); // id|null
        $status = $request->get('status'); // active|inactive|null
        $trashed = $request->get('trashed', 'without'); // without|with|only

        $query = Product::query()
            ->with([
                'category:id,name',
                'brand:id,name',
                'primaryImage:id,product_id,path,is_primary',
            ])
            ->when($category !== null && $category !== '', fn ($q) => $q->where('category_id', $category))
            ->when($brand !== null && $brand !== '', fn ($q) => $q->where('brand_id', $brand))
            ->when($status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($search !== '', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });

        // trashed filter
        if ($trashed === 'with') {
            $query->withTrashed();
        } elseif ($trashed === 'only') {
            $query->onlyTrashed();
        }

        $products = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Product $p) => [
                'id' => $p->id,
                'sku' => $p->sku,
                'name' => $p->name,
                'slug' => $p->slug,
                'is_active' => (bool) $p->is_active,
                'currency' => $p->currency,
                'price_amount' => (int) $p->price_amount,
                'price_decimal' => $p->price_decimal, // accessor
                'stock_on_hand' => (int) $p->stock_on_hand,
                'category' => $p->category ? ['id' => $p->category->id, 'name' => $p->category->name] : null,
                'brand' => $p->brand ? ['id' => $p->brand->id, 'name' => $p->brand->name] : null,
                'primary_image' => $p->primaryImage ? [
                    'path' => $p->primaryImage->path,
                ] : null,
                'deleted_at' => $p->deleted_at?->toDateTimeString(),
            ]);

        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        $brands = Brand::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('admin/products/Index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => [
                'search' => $search,
                'category' => $category,
                'brand' => $brand,
                'status' => $status,
                'trashed' => $trashed,
            ],
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Product::class);

        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        $brands = Brand::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('admin/products/Create', [
            'categories' => $categories,
            'brands' => $brands,
            'defaults' => [
                'currency' => 'PEN',
                'is_active' => true,
                'stock_on_hand' => 0,
            ],
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        Gate::authorize('create', Product::class);

        $data = $request->validated();

        // Convert price (soles) -> cents
        // Accepts "19.90", 19.9, "19", etc.
        $price = (string) $data['price'];
        $normalized = preg_replace('/[^0-9.]/', '', $price) ?? '0';
        $priceAmount = (int) round(((float) $normalized) * 100);

        if ($priceAmount < 0) {
            return back()->with('error', 'El precio es inválido.');
        }

        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $i = 2;

        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$i}";
            $i++;
        }

        $product = Product::create([
            'category_id' => (int) $data['category_id'],
            'brand_id' => $data['brand_id'] ? (int) $data['brand_id'] : null,
            'sku' => $data['sku'],
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'price_amount' => $priceAmount,
            'currency' => $data['currency'] ?? 'PEN',
            'stock_on_hand' => (int) $data['stock_on_hand'],
            'is_active' => (bool) $data['is_active'],
        ]);

        // MVP: no registramos inventory_movements todavía (lo hacemos en v2 o cuando hagamos "ajustes de stock")

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(Product $product) { abort(501); }

    public function edit(Product $product): Response
    {
        Gate::authorize('update', $product);

        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        $brands = Brand::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('admin/products/Edit', [
            'product' => [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'brand_id' => $product->brand_id,
                'sku' => $product->sku,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price_amount' => (int) $product->price_amount,
                'price' => number_format($product->price_amount / 100, 2, '.', ''), // UI soles
                'currency' => $product->currency,
                'stock_on_hand' => (int) $product->stock_on_hand,
                'is_active' => (bool) $product->is_active,
                'deleted_at' => $product->deleted_at?->toDateTimeString(),
            ],
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);

        $data = $request->validated();

        // price soles -> cents
        $price = (string) $data['price'];
        $normalized = preg_replace('/[^0-9.]/', '', $price) ?? '0';
        $priceAmount = (int) round(((float) $normalized) * 100);

        if ($priceAmount < 0) {
            return back()->with('error', 'El precio es inválido.');
        }

        $product->update([
            'category_id' => (int) $data['category_id'],
            'brand_id' => $data['brand_id'] ? (int) $data['brand_id'] : null,
            'sku' => $data['sku'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price_amount' => $priceAmount,
            'currency' => $data['currency'] ?? 'PEN',
            'stock_on_hand' => (int) $data['stock_on_hand'],
            'is_active' => (bool) $data['is_active'],
        ]);

        // MVP: slug estable (no se cambia automáticamente)
        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        // Regla MVP recomendada (opcional): si ya tuvo movimientos, no permitir borrar.
        // Si prefieres permitir, comenta este bloque.
        if ($product->inventoryMovements()->exists()) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'No puedes eliminar un producto con movimientos de inventario.');
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }


    public function restore(Product $product)
    {
        Gate::authorize('update', $product);

        $product->restore();

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Producto restaurado correctamente.');
    }

}

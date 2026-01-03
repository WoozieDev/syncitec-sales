<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request): Response
    {
        // Si creaste permissions.view:
        // Gate::authorize('viewAny', Permission::class);

        // Si NO creaste policy/permiso para permissions, usa roles.view como mínimo:
        Gate::authorize('viewAny', \Spatie\Permission\Models\Role::class);

        $search = trim((string) $request->get('search', ''));

        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString()
            ->through(fn (Permission $p) => [
                'id' => $p->id,
                'name' => $p->name,
                'module' => str_contains($p->name, '.') ? explode('.', $p->name)[0] : 'other',
            ]);

        return Inertia::render('admin/permissions/Index', [
            'permissions' => $permissions,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }
}

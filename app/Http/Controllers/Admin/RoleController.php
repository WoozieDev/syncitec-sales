<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private function guard(): string
    {
        return 'web';
    }

    private function ensureNotProtected(Role $role): void
    {
        if ($role->name === 'superadmin') {
            abort(403, 'This role is protected.');
        }
    }

    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Role::class);

        $search = trim((string) $request->get('search', ''));

        $roles = Role::query()
            ->where('guard_name', $this->guard())
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->withCount('users')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (Role $role) => [
                'id' => $role->id,
                'name' => $role->name,
                'users_count' => $role->users_count,
                'is_protected' => $role->name === 'superadmin',
            ]);

        return Inertia::render('admin/roles/Index', [
            'roles' => $roles,
            'filters' => ['search' => $search],
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Role::class);

        $permissions = Permission::query()
            ->where('guard_name', $this->guard())
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($p) => ['id' => $p->id, 'name' => $p->name]);

        return Inertia::render('admin/roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    public function store(StoreRoleRequest $request)
    {
        Gate::authorize('create', Role::class);

        $data = $request->validated();

        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => $this->guard(),
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $role): Response
    {
        Gate::authorize('update', $role);
        $this->ensureNotProtected($role);

        $permissions = Permission::query()
            ->where('guard_name', $this->guard())
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($p) => ['id' => $p->id, 'name' => $p->name]);

        return Inertia::render('admin/roles/Edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions()->pluck('name')->values(),
                'is_protected' => $role->name === 'superadmin',
            ],
            'permissions' => $permissions,
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        Gate::authorize('update', $role);
        $this->ensureNotProtected($role);

        $data = $request->validated();

        $role->update([
            'name' => $data['name'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()
            ->route('admin.roles.edit', $role)
            ->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role)
    {
        Gate::authorize('delete', $role);
        $this->ensureNotProtected($role);

        if ($role->users()->exists()) {
            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'No puedes eliminar un rol que tiene usuarios asignados.');
        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }
}

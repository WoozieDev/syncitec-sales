<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->get('search', ''));
        $role = $request->get('role'); // role name
        $status = $request->get('status'); // active|inactive
        $trashed = $request->get('trashed', 'without'); // without|with|only

        $query = User::query()
            ->select(['id', 'name', 'email', 'is_active', 'created_at', 'deleted_at'])
            ->with(['roles:id,name'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($role, function ($q) use ($role) {
                $q->whereHas('roles', fn ($rq) => $rq->where('name', $role));
            });

        // Soft delete filters
        if ($trashed === 'only') {
            $query->onlyTrashed();
        } elseif ($trashed === 'with') {
            $query->withTrashed();
        } else {
            // without (default) => do nothing
        }

        $users = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => (bool) $user->is_active,
                'deleted_at' => $user->deleted_at,
                'roles' => $user->roles->pluck('name')->values(),
                'created_at' => $user->created_at?->toDateTimeString(),
            ]);

        $roles = Role::query()
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($r) => ['id' => $r->id, 'name' => $r->name]);

        return Inertia::render('admin/users/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => [
                'search' => $search,
                'role' => $role,
                'status' => $status,
                'trashed' => $trashed,
            ],
        ]);
    }

    // placeholders: implementaremos paso a paso
    public function create(): Response
    {
        $roles = Role::query()
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($r) => ['id' => $r->id, 'name' => $r->name]);

        return Inertia::render('admin/users/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => (bool) $data['is_active'],
        ]);

        // rol único (MVP)
        $user->syncRoles([$data['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function show(User $user): Response
    {
        return Inertia::render('admin/users/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => (bool) $user->is_active,
                'roles' => $user->getRoleNames()->values(),
                'created_at' => $user->created_at?->toDateTimeString(),
                'deleted_at' => $user->deleted_at?->toDateTimeString(),
            ],
        ]);
    }

    public function edit(User $user): Response
    {
        $roles = Role::query()
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($r) => ['id' => $r->id, 'name' => $r->name]);

        return Inertia::render('admin/users/Edit', [
            'roles' => $roles,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => (bool) $user->is_active,
                'role' => $user->getRoleNames()->first(), // rol único (MVP)
            ],
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'is_active' => (bool) $data['is_active'],
        ]);

        // password opcional
        if (!empty($data['password'])) {
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        // rol único (MVP)
        $user->syncRoles([$data['role']]);

        return redirect()
            ->route('admin.users.edit', $user)
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Usuario restaurado correctamente.');
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function viewAny(User $auth): bool
    {
        return $auth->can('roles.view');
    }

    public function view(User $auth, Role $role): bool
    {
        return $auth->can('roles.view');
    }

    public function create(User $auth): bool
    {
        return $auth->can('roles.create');
    }

    public function update(User $auth, Role $role): bool
    {
        if (! $auth->can('roles.update')) return false;
        if ($role->name === 'superadmin') return false;

        return true;
    }

    public function delete(User $auth, Role $role): bool
    {
        if (! $auth->can('roles.delete')) return false;
        if ($role->name === 'superadmin') return false;

        return true;
    }
}

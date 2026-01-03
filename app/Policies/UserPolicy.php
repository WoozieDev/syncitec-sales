<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $auth): bool
    {
        return $auth->can('users.view');
    }

    public function view(User $auth, User $user): bool
    {
        return $auth->can('users.view');
    }

    public function create(User $auth): bool
    {
        return $auth->can('users.create');
    }

    public function update(User $auth, User $user): bool
    {
        if (! $auth->can('users.update')) {
            return false;
        }

        // Protect superadmin target
        if ($user->hasRole('superadmin')) {
            return false;
        }

        return true;
    }

    public function delete(User $auth, User $user): bool
    {
        if (! $auth->can('users.delete')) {
            return false;
        }

        // Protect superadmin target
        if ($user->hasRole('superadmin')) {
            return false;
        }

        // Optional: prevent self-delete
        if ($auth->id === $user->id) {
            return false;
        }

        return true;
    }

    public function restore(User $auth, User $user): bool
    {
        // treat restore as update permission
        if (! $auth->can('users.update')) {
            return false;
        }

        if ($user->hasRole('superadmin')) {
            return false;
        }

        return true;
    }
}

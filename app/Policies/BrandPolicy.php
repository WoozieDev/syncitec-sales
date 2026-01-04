<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;

class BrandPolicy
{
    public function viewAny(User $auth): bool
    {
        return $auth->can('brands.view');
    }

    public function view(User $auth, Brand $brand): bool
    {
        return $auth->can('brands.view');
    }

    public function create(User $auth): bool
    {
        return $auth->can('brands.create');
    }

    public function update(User $auth, Brand $brand): bool
    {
        return $auth->can('brands.update');
    }

    public function delete(User $auth, Brand $brand): bool
    {
        return $auth->can('brands.delete');
    }
}

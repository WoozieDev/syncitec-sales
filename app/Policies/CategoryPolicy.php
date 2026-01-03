<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $auth): bool
    {
        return $auth->can('categories.view');
    }

    public function view(User $auth, Category $category): bool
    {
        return $auth->can('categories.view');
    }

    public function create(User $auth): bool
    {
        return $auth->can('categories.create');
    }

    public function update(User $auth, Category $category): bool
    {
        return $auth->can('categories.update');
    }

    public function delete(User $auth, Category $category): bool
    {
        return $auth->can('categories.delete');
    }
}

<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $auth): bool
    {
        return $auth->can('products.view');
    }

    public function view(User $auth, Product $product): bool
    {
        return $auth->can('products.view');
    }

    public function create(User $auth): bool
    {
        return $auth->can('products.create');
    }

    public function update(User $auth, Product $product): bool
    {
        return $auth->can('products.update');
    }

    public function delete(User $auth, Product $product): bool
    {
        return $auth->can('products.delete');
    }
}

<?php

namespace App\UseCases\Interfaces\Services;

use App\Entity\Product;
use App\Entity\User;

interface ICartService
{
    public function getProductsByUserWithFilter(User $user, string $filterTerm = ''): array;

    public function addProduct(Product $product, User $user, int $quantity): void;

    public function removeProduct(Product $product, User $user, int $quantity): void;

    public function removeAllProductsFromCart(): void;

    public function getCartProductsByUser(User $user): array;
}

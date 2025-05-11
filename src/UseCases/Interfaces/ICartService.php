<?php
namespace App\UseCases\Interfaces;

use App\Entity\Product;
use App\Entity\User;

interface ICartService
{
    public function addProduct(Product $product, User $user, int $quantity): void;
    public function removeProduct(Product $product, User $user, int $quantity): void;
    public function getCartProductsByUser(User $user): array;
    public function getProductsByUserWithFilter(User $user, string $filterTerm): array;
     public function removeAllProductsFromCart() : void;
}
<?php
namespace App\UseCases\Interfaces;

use App\Entity\Product;
use App\Entity\User;

interface ICartService
{
    public function addProduct(Product $product, User $user): void;
    public function removeProduct(Product $product, User $user): void;
    public function getProductsByUser(User $user): array;
    public function getProductsByUserWithFilter(User $user, string $filterTerm): array;
    public function placeOrder(User $user): void;
}
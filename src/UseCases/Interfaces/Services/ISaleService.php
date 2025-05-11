<?php

namespace App\UseCases\Interfaces\Services;

use App\Entity\CartProduct;
use App\Entity\Sale;
use App\Entity\User;

interface ISaleService
{
    public function createSale(User $user): Sale;

    /**
     * @param Sale $sale
     * @param CartProduct[] $products
     */
    public function assignProductsOnSale(Sale $sale, array $products): void;

    public function getSaleProducts(User $user, string $saleId): array;

    public function getSalesAndProductsByUser(User $user): array;

    /**
     * @param CartProduct[] $products
     */
    public function placeOrder(User $user, array $products): void;
}
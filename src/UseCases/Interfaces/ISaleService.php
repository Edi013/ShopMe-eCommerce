<?php

namespace App\UseCases\Interfaces;

use App\Entity\Product;
use App\Entity\Sale;
use App\Entity\SaleProduct;
use App\Entity\User;

interface ISaleService
{
    public function createSale(User $user): Sale;
    public function assignProductsOnSale($sale, $products): void;

    public function getSalesAndProductsByUser(User $user): array;

    public function getSaleProducts(User $user, string $saleId): array;

}
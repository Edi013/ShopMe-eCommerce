<?php

namespace App\UseCases\Interfaces\Services;

use Symfony\Component\HttpFoundation\Request;

interface IProductService
{
    public function getProducts(Request $request): array;

    public function findById(string $productId);
}

<?php
namespace App\UseCases\Services;

use App\Repository\ProductRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProducts(Request $request)
    {
        $searchTerm = $request->query->get('q', '');

        $products = $this->productRepository->findAll();

        if ($searchTerm) {
            $products = array_filter($products, function ($product) use ($searchTerm) {
                return stripos($product->getTitle(), $searchTerm) !== false;
            });
        }

        return [
            'products' => $products,
            'searchTerm' => $searchTerm
        ];
    }

    public function findById(string $productId)
    {
        return $this->productRepository->find(Uuid::fromString($productId));
    }
}

<?php
namespace App\Service;

use App\Repository\ProductRepository;
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
        // Get the search term from the query parameters
        $searchTerm = $request->query->get('q', '');

        // Fetch products from the repository
        $products = $this->productRepository->findAll();

        // If a search term is provided, filter the products
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
}

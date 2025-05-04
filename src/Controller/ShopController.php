<?php
namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    #[Route('/shop', name: 'shop')]
    public function index(Request $request): Response
    {
        // Get products from the ProductService
        $data = $this->productService->getProducts($request);

        return $this->render('shop/index.html.twig', [
            'shopItems' => $data['products'],
            'searchTerm' => $data['searchTerm']
        ]);
    }
}

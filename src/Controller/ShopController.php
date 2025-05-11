<?php
namespace App\Controller;

use App\Common\UserSession;
use App\Entity\Product;
use App\UseCases\Services\CartService;
use App\UseCases\Services\ProductService;
use App\UseCases\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    private ProductService $productService;
    private CartService $cartService;

    private UserService $userService;

    public function __construct(ProductService $productService, CartService $cartService, UserService $userService)
    {
        $this->productService = $productService;
        $this->cartService = $cartService;
        $this->userService = $userService;
    }

    #[Route('/shop', name: 'shop')]
    public function index(Request $request): Response
    {
        $data = $this->productService->getProducts($request);

        return $this->render('shop/index.html.twig', [
            'shopItems' => $data['products'],
            'searchTerm' => $data['searchTerm']
        ]);
    }

    #[Route('/add-to-cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request): void
    {
        $productId = $request->request->get('product_id');
        $quantity = (int) $request->request->get('quantity', 1);

        $product = $this->productService->findById($productId);
        if (!$product) {
            $this->addFlash('error', 'Product not found!');
            return ;
        }

        $userId = UserSession::getUserId($request->getSession());
        $user = $this->userService->findByUserId($userId);

        $this->cartService->addProduct($product, $user);

        $this->addFlash('success', 'Item added to your cart!');
        sleep(0.5);
    }
}

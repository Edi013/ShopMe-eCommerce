<?php
namespace App\Controller;

use App\Common\UserSession;
use App\UseCases\Services\CartService;
use App\UseCases\Services\ProductService;
use App\UseCases\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private CartService $cartService;
    private ProductService $productService;
    private UserService $userService;
    private RequestStack $requestStack;

    public function __construct(CartService $cartService, ProductService $productService, UserService $userService, RequestStack $requestStack)
    {
        $this->cartService = $cartService;
        $this->productService = $productService;
        $this->userService = $userService;
        $this->requestStack = $requestStack;
    }

    #[Route('/cart', name: 'cart')]
    public function index(): Response
    {
        $session = $this->requestStack->getSession();
        $userId = UserSession::getUserId($session);
        $user = $this->userService->findByUserId($userId);

        $cartProducts = $this->cartService->getProductsByUser($user);

        $products = [];
        foreach ($cartProducts as $cartProduct) {
            $product = $this->productService->findById($cartProduct->getProduct()->getId());
            if ($product) {
                $products[] = [
                    'title' => $product->getTitle(),
                    'description' => $product->getDescription(),
                    'price' => $product->getPrice(),
                    'quantity' => $cartProduct->getQuantity(),
                ];
            }
        }

        return $this->render('cart/index.html.twig', [
            'products' => $products,
        ]);
    }
}
<?php
namespace App\Controller;

use App\Common\UserSession;
use App\UseCases\Services\CartService;
use App\UseCases\Services\ProductService;
use App\UseCases\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
                    'id' => $product->getId(),
                    'title' => $product->getTitle(),
                    'description' => $product->getDescription(),
                    'price' => $product->getPrice(),
                    'quantity' => $cartProduct->getQuantity(),
                    'stock' => $product->getStock(),
                ];
            }
        }

        return $this->render('cart/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/remove-from-cart/{productId}', name: 'remove-from-cart', methods: ['POST'])]
    public function removeFromCart(Request $request): RedirectResponse
    {
        $productId = $request->attributes->get('productId');
        $quantity = (int) $request->request->get('quantity', 1);

        $product = $this->productService->findById($productId);
        if (!$product) {
            $this->addFlash('error', 'Product not found!');
            return $this->redirectToRoute('cart');
        }
        $userId = UserSession::getUserId($request->getSession());
        $user = $this->userService->findByUserId($userId);

        $this->cartService->removeProduct($product, $user, $quantity);

        $this->addFlash('success', 'Item deleted from cart!');
        return $this->redirectToRoute('cart');
    }
}
<?php
namespace App\Controller;

use App\Common\UserSession;
use App\Entity\Enums\Constants;
use App\UseCases\Interfaces\Services\ICartService;
use App\UseCases\Interfaces\Services\IProductService;
use App\UseCases\Interfaces\Services\ISaleService;
use App\UseCases\Interfaces\Services\IUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private ICartService $cartService;
    private IProductService $productService;
    private IUserService $userService;
    private ISaleService $saleService;
    private RequestStack $requestStack;

    public function __construct(
        ICartService $cartService,
        IProductService $productService,
        IUserService $userService,
        RequestStack $requestStack,
        ISaleService $saleService
    ) {
        $this->cartService = $cartService;
        $this->productService = $productService;
        $this->userService = $userService;
        $this->requestStack = $requestStack;
        $this->saleService = $saleService;
    }

    #[Route('/cart', name: 'cart')]
    public function index(): Response
    {
        $session = $this->requestStack->getSession();
        $userId = UserSession::getUserId($session);
        $user = $this->userService->findByUserId($userId);

        $cartProducts = $this->cartService->getCartProductsByUser($user);

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

    #[Route('/place-order', name: 'place-order', methods: ['POST'])]
    public function placeOrder(Request $request): RedirectResponse
    {
        $userId = UserSession::getUserId($request->getSession());
        $user = $this->userService->findByUserId($userId);
        $cartProducts = $this->cartService->getCartProductsByUser($user);

        try{
            $result = $this->saleService->placeOrder($user, $cartProducts);
            if($result === Constants::SUCCESS){
                $this->cartService->removeAllProductsFromCart();
                $this->addFlash('success', 'Ordered placed!');
                return $this->redirectToRoute('home');            }
            if($result === Constants::NOT_ENOUGH_STOCK){
                $this->addFlash('error', Constants::NOT_ENOUGH_STOCK->message());
                return $this->redirectToRoute('cart');
            }

            $this->addFlash('error', 'Order was not placed!');
            return $this->redirectToRoute('cart');
        }
        catch (\Exception $e){
            $this->addFlash('error', 'Error placing order. Try later.');
            return $this->redirectToRoute('cart');
        }
    }
}
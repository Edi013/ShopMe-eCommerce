<?php

namespace App\UseCases\Services;

use App\Entity\CartProduct;
use App\Entity\Product;
use App\Entity\Sale;
use App\Entity\SaleProduct;
use App\Entity\User;
use App\Repository\CartProductRepository;
use App\Repository\SaleProductRepository;
use App\Repository\SaleRepository;
use App\UseCases\Interfaces\ICartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CartService implements ICartService
{
    private EntityManagerInterface $entityManager;
    private CartProductRepository $cartRepository;
    private SaleRepository $saleRepository;
    private SaleProductRepository $saleProductRepository;

    public function __construct(EntityManagerInterface $em, CartProductRepository $cartRepository)
    {
        $this->entityManager = $em;
        $this->cartRepository = $cartRepository;
    }

    public function getProductsByUserWithFilter(User $user, string $filterTerm = ''): array
    {
        $products = $this->getProductsByUser($user);

        if ($filterTerm !== '') {
            $products = array_filter($products, function ($product) use ($filterTerm) {
                return stripos($product->getTitle(), $filterTerm) !== false;
            });
        }

        return [
            'products' => $products,
            'searchTerm' => $filterTerm
        ];
    }

    public function addProduct(Product $product, User $user, int $quantity): void
    {
        $cartProduct = $this->cartRepository->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        if ($cartProduct) {
            $cartProduct->setQuantity($cartProduct->getQuantity() + $quantity);
        } else {
            $cartProduct = new CartProduct();
            $cartProduct->setUser($user);
            $cartProduct->setProduct($product);
            $cartProduct->setQuantity(1);
            $cartProduct->setCreatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($cartProduct);
        }

        $this->entityManager->flush();
    }

    public function removeProduct(Product $product, User $user,int $quantity): void
    {
        $cartProduct = $this->cartRepository->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        if ($cartProduct) {
            if($cartProduct->getQuantity() - $quantity < 1){
                $this->entityManager->remove($cartProduct);
            }else{
                $cartProduct->setQuantity($cartProduct->getQuantity() - $quantity);
            }
            $this->entityManager->flush();
        }
    }
    public function removeAllProductsFromCart() : void
    {
        $this->entityManager->beginTransaction();
        try {
            $cartProducts = $this->cartRepository->findAll();
            foreach ($cartProducts as $cartProduct) {
                $this->entityManager->remove($cartProduct);
            }
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch ( \Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }

    public function getProductsByUser(User $user): array
    {
        return $this->cartRepository->findByUser($user);
    }
}

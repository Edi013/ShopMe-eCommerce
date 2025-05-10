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
    private EntityManagerInterface $em;
    private CartProductRepository $cartRepository;
    private SaleRepository $saleRepository;
    private SaleProductRepository $saleProductRepository;

    public function __construct(EntityManagerInterface $em, CartProductRepository $cartRepository)
    {
        $this->em = $em;
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

    public function addProduct(Product $product, User $user): void
    {
        $cartProduct = $this->cartRepository->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        if ($cartProduct) {
            $cartProduct->setQuantity($cartProduct->getQuantity() + 1);
        } else {
            $cartProduct = new CartProduct();
            $cartProduct->setUser($user);
            $cartProduct->setProduct($product);
            $cartProduct->setQuantity(1);
            $cartProduct->setCreatedAt(new \DateTimeImmutable());
            $this->em->persist($cartProduct);
        }

        $this->em->flush();
    }

    public function removeProduct(Product $product, User $user): void
    {
        $cartProduct = $this->cartRepository->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        if ($cartProduct) {
            $this->em->remove($cartProduct);
            $this->em->flush();
        }
    }

    public function getProductsByUser(User $user): array
    {
        return $this->cartRepository->findByUser($user);
    }

    public function placeOrder($user): void
    {
        $products = $this->getProductsByUser($user);

        $this->em->beginTransaction();
        try {
            $sale = new Sale();
            $sale->setUser($user);
            $sale->setCreatedAt(new \DateTimeImmutable());
            $this->em->persist($sale);
            $this->em->flush();

            foreach ($products as $product) {
                $saleProduct = new SaleProduct();
                $saleProduct->setSale($sale);
                $saleProduct->setProduct($product);
                $saleProduct->setQuantity($product->getQuantity());
                $this->em->persist($saleProduct);
            }
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

    }
}

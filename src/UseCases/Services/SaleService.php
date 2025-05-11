<?php

namespace App\UseCases\Services;

use App\Entity\CartProduct;
use App\Entity\SaleProduct;
use App\Entity\User;
use App\Entity\Sale;
use App\Repository\SaleProductRepository;
use App\Repository\SaleRepository;
use App\UseCases\Interfaces\ISaleService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

class SaleService implements  ISaleService
{
    private $entityManager;
    private SaleRepository $saleRepository;
    private SaleProductRepository $saleProductRepository;
    private ProductService $productService;
    private RequestStack $requestStack;


    public function __construct(EntityManagerInterface $entityManager, SaleRepository $saleRepository, SaleProductRepository $saleProductRepository,
                                ProductService $productService, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->saleRepository = $saleRepository;
        $this->saleProductRepository = $saleProductRepository;
        $this->productService = $productService;
        $this->requestStack = $requestStack;
    }


    public function createSale(User $user): Sale
    {
        $sale = new Sale();
        $sale->setUser($user);
        $sale->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($sale);
        return $sale;
    }

    /**
     * @param CartProduct[] $products
     */
    public function assignProductsOnSale($sale, $products): void
    {
        foreach ($products as $cartProduct) {
            $product = $cartProduct->getProduct();
            if ($cartProduct->getStock() < $quantity) {
                $this->requestStack->getSession()->getFlashBag()->add(
                    'error',
                    sprintf('Not enough stock for "%s". Ordered: %d, Available: %d',
                        $cartProduct->getTitle(),
                        $quantity,
                        $cartProduct->getStock()
                    )
                );
                continue; // Skip this product
            }

            $saleProduct = new SaleProduct();
            $saleProduct->setSale($sale);
            $saleProduct->setProduct($cartProduct);
            $saleProduct->setQuantity($cartProduct->getQuantity());
            $this->entityManager->persist($saleProduct);
        }
    }

    public function getSaleProducts(User $user, string $saleId): array
    {
        return $this->saleProductRepository->findBySaleId($saleId);
    }

    public function getSalesAndProductsByUser(User $user): array
    {
        $sales =  $this->saleRepository->findBy(['user' => $user]);
        $salesData = [];
        $totalPrice = 0;
        foreach ($sales as $sale) {
            foreach ($sale->getSaleProducts() as $saleProduct) {
                $product = $saleProduct->getProduct();
                $salesData[] = [
                    'saleId' => $sale->getId(),
                    'createdAt' => $sale->getCreatedAt()->format('Y-m-d H:i:s'),
                    'productTitle' => $product->getTitle(),
                    'quantity' => $saleProduct->getQuantity(),
                    'price' => $product->getPrice(),
                    'description' => $product->getDescription(),
                ];
                $totalPrice += $product->getPrice() * $saleProduct->getQuantity();
            }
        }
        return [
            'sales' => $salesData,
            'totalPrice' => $totalPrice
        ];
    }

    /**
     * @param CartProduct[] $products
     */
    public function placeOrder($user, array $products): void
    {
        $this->entityManager->beginTransaction();
        try {
            $sale = $this->createSale($user);
            $this->assignProductsOnSale($sale, $products);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch ( Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
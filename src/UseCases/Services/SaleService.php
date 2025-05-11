<?php

namespace App\UseCases\Services;

use App\Entity\SaleProduct;
use App\Entity\User;
use App\Entity\Sale;
use App\Repository\SaleProductRepository;
use App\Repository\SaleRepository;
use App\UseCases\Interfaces\ISaleService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class SaleService implements  ISaleService
{
    private $entityManager;
    private SaleRepository $saleRepository;
    private SaleProductRepository $saleProductRepository;

    public function __construct(EntityManagerInterface $entityManager, SaleRepository $saleRepository, SaleProductRepository $saleProductRepository)
    {
        $this->entityManager = $entityManager;
        $this->saleRepository = $saleRepository;
        $this->saleProductRepository = $saleProductRepository;
    }


    public function createSale(User $user): Sale
    {
        $sale = new Sale();
        $sale->setUser($user);
        $sale->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($sale);
        return $sale;
    }

    public function assignProductsOnSale($sale, $products): void
    {
        foreach ($products as $product) {
            $saleProduct = new SaleProduct();
            $saleProduct->setSale($sale);
            $saleProduct->setProduct($product);
            $saleProduct->setQuantity($product->getQuantity());
            $this->entityManager->persist($saleProduct);
        }
    }

    public function getSaleProducts(User $user, string $saleId): array
    {
        return $this->saleProductRepository->findBySaleId($saleId);
    }

    public function getSalesByUser(User $user): array
    {
        return $this->saleRepository->findBy(['user' => $user]);
    }

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
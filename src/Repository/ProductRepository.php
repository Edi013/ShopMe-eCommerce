<?php
namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Find active products
     */
    public function findActive(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.active = true')
            ->orderBy('p.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Search products by title
     */
    public function searchByTitle(string $term): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.title LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery()
            ->getResult();
    }
}

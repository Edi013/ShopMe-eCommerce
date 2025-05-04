<?php


namespace App\Repository;

use App\Entity\SaleProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SaleProduct>
 *
 * @method SaleProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaleProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaleProduct[]    findAll()
 * @method SaleProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaleProduct::class);
    }

    // Example custom query: find all sale products by sale ID
    public function findBySaleId(string $saleId): array
    {
        return $this->createQueryBuilder('sp')
            ->andWhere('sp.sale = :saleId')
            ->setParameter('saleId', $saleId)
            ->getQuery()
            ->getResult();
    }
}

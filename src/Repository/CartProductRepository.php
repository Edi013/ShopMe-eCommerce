<?php
namespace App\Repository;

use App\Entity\CartProduct;
use App\Entity\User;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartProduct>
 */
class CartProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartProduct::class);
    }

    /**
     * Find cart products for a specific user
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('cp')
            ->andWhere('cp.user = :user')
            ->setParameter('user', $user)
            ->orderBy('cp.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find a specific cart item by user and product
     */
    public function findOneByUserAndProduct(User $user, Product $product): ?CartProduct
    {
        return $this->createQueryBuilder('cp')
            ->andWhere('cp.user = :user')
            ->andWhere('cp.product = :product')
            ->setParameters([
                'user' => $user,
                'product' => $product,
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}

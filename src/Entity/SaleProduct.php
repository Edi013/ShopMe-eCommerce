<?php

namespace App\Entity;

use App\Repository\SaleProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaleProductRepository::class)]
#[ORM\Table(name: 'sale_products')]
class SaleProduct
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Sale::class, inversedBy: 'saleProducts')]
    #[ORM\JoinColumn(name: 'sale_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Sale $sale = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Product $product = null;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    public function getSale(): ?Sale
    {
        return $this->sale;
    }

    public function setSale(?Sale $sale): self
    {
        $this->sale = $sale;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}

<?php


namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: SaleRepository::class)]
#[ORM\Table(name: 'sales')]
class Sale
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'Ramsey\Uuid\Doctrine\UuidGenerator')]
    private ?UuidInterface $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'sales')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\OneToMany(mappedBy: 'sale', targetEntity: SaleProduct::class, cascade: ['persist', 'remove'])]
    private Collection $saleProducts;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->saleProducts = new ArrayCollection();
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return Collection|SaleProduct[]
     */
    public function getSaleProducts(): Collection
    {
        return $this->saleProducts;
    }

    public function addSaleProduct(SaleProduct $saleProduct): self
    {
        if (!$this->saleProducts->contains($saleProduct)) {
            $this->saleProducts[] = $saleProduct;
            $saleProduct->setSale($this);
        }
        return $this;
    }

    public function removeSaleProduct(SaleProduct $saleProduct): self
    {
        if ($this->saleProducts->removeElement($saleProduct)) {
            if ($saleProduct->getSale() === $this) {
                $saleProduct->setSale(null);
            }
        }
        return $this;
    }
}

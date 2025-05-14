<?php


namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?UuidInterface $id = null;

    #[ORM\Column(type: 'string', unique: true)]
    #[Assert\NotBlank]
    private string $userName;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private string $password;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'integer', options: ['default' => 1])]
    private int $role_id;

    #[ORM\OneToMany(targetEntity: Sale::class, mappedBy: 'user',  cascade: ['persist', 'remove'])]
    private Collection $sales;

    #[ORM\OneToMany(targetEntity: CartProduct::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $cartProducts;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->sales = new ArrayCollection();
        $this->cartProducts = new ArrayCollection();
    }

    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function addCartProduct(Product $product, int $quantity): self
    {
        // Check if this cart product already exists for the user and product combination
        $existingCartProduct = $this->cartProducts->filter(function (CartProduct $cartProduct) use ($product) {
            return $cartProduct->getProduct() === $product;
        })->first();

        if ($existingCartProduct) {
            // If product already exists, update quantity
            $existingCartProduct->setQuantity($existingCartProduct->getQuantity() + $quantity);
        } else {
            // If a product doesn't exist, create a new CartProduct
            $cartProduct = new CartProduct($this, $product, $quantity);
            $this->cartProducts[] = $cartProduct;
        }

        return $this;
    }

    public function removeCartProduct(Product $product): self
    {
        $cartProduct = $this->cartProducts->filter(function (CartProduct $cartProduct) use ($product) {
            return $cartProduct->getProduct() === $product;
        })->first();

        if ($cartProduct) {
            $this->cartProducts->removeElement($cartProduct);
        }

        return $this;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
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

    public function getRoleId(): int
    {
        return $this->role_id;
    }
}

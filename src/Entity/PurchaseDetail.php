<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PurchaseDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;

#[ApiFilter(SearchFilter::class, properties : ['purchase' => 'exact'])]
#[ORM\Entity(repositoryClass: PurchaseDetailRepository::class)]
#[ApiResource]
class PurchaseDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $unitPrice = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Purchasing $purchase = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    private ?Game $game = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?ActivationCode $activationCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): static
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getPurchase(): ?Purchasing
    {
        return $this->purchase;
    }

    public function setPurchase(Purchasing $purchase): static
    {
        $this->purchase = $purchase;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getActivationCode(): ?ActivationCode
    {
        return $this->activationCode;
    }

    public function setActivationCode(ActivationCode $activationCode): static
    {
        $this->activationCode = $activationCode;

        return $this;
    }
}

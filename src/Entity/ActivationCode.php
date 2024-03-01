<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ActivationCodeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivationCodeRepository::class)]
#[ApiResource]
class ActivationCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $distributionDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $duration = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $isAvailable = true; 

    #[ORM\ManyToOne(inversedBy: 'activationCodes')]
    private ?Game $game = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getDistributionDate(): ?\DateTimeInterface
    {
        return $this->distributionDate;
    }

    public function setDistributionDate(\DateTimeInterface $distributionDate): static
    {
        $this->distributionDate = $distributionDate;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): static
    {
        $this->duration = $duration;

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

    public function isAvailable(): ?bool
    {
    return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): self
    {
    $this->isAvailable = $isAvailable;

    return $this;
    }
}

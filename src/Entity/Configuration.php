<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ConfigurationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConfigurationRepository::class)]
#[ApiResource]
class Configuration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('get')]
    private ?string $operatingSystem = null;

    #[ORM\Column(length: 255)]
    #[Groups('get')]
    private ?string $processor = null;

    #[ORM\Column(length: 255)]
    #[Groups('get')]
    private ?string $graphicsCard = null;

    #[ORM\Column(length: 255)]
    #[Groups('get')]
    private ?string $ramMemory = null;

    #[ORM\Column(length: 255)]
    #[Groups('get')]
    private ?string $storage = null;

    #[ORM\Column(length: 255)]
    #[Groups('get')]
    private ?string $directX = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('get')]
    private ?string $additionalNotes = null;

    #[ORM\Column(length: 255)]
    #[Groups('get')]
    private ?string $Display = null;

    #[ORM\OneToMany(mappedBy: 'Configuration', targetEntity: Game::class)]
    private Collection $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOperatingSystem(): ?string
    {
        return $this->operatingSystem;
    }

    public function setOperatingSystem(string $operatingSystem): static
    {
        $this->operatingSystem = $operatingSystem;

        return $this;
    }

    public function getProcessor(): ?string
    {
        return $this->processor;
    }

    public function setProcessor(string $processor): static
    {
        $this->processor = $processor;

        return $this;
    }

    public function getGraphicsCard(): ?string
    {
        return $this->graphicsCard;
    }

    public function setGraphicsCard(string $graphicsCard): static
    {
        $this->graphicsCard = $graphicsCard;

        return $this;
    }

    public function getRamMemory(): ?string
    {
        return $this->ramMemory;
    }

    public function setRamMemory(string $ramMemory): static
    {
        $this->ramMemory = $ramMemory;

        return $this;
    }

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function setStorage(string $storage): static
    {
        $this->storage = $storage;

        return $this;
    }

    public function getDirectX(): ?string
    {
        return $this->directX;
    }

    public function setDirectX(string $directX): static
    {
        $this->directX = $directX;

        return $this;
    }

    public function getAdditionalNotes(): ?string
    {
        return $this->additionalNotes;
    }

    public function setAdditionalNotes(string $additionalNotes): static
    {
        $this->additionalNotes = $additionalNotes;

        return $this;
    }

    public function getDisplay(): ?string
    {
        return $this->Display;
    }

    public function setDisplay(string $Display): static
    {
        $this->Display = $Display;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setConfiguration($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getConfiguration() === $this) {
                $game->setConfiguration(null);
            }
        }

        return $this;
    }
}

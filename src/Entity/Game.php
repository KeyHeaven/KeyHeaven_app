<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $exitDate = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'games')]
    private Collection $Category;

    #[ORM\ManyToMany(targetEntity: Platform::class, inversedBy: 'games')]
    private Collection $platform;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Review::class)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: ActivationCode::class)]
    private Collection $activationCodes;

    public function __construct()
    {
        $this->Category = new ArrayCollection();
        $this->platform = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->activationCodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getExitDate(): ?\DateTimeInterface
    {
        return $this->exitDate;
    }

    public function setExitDate(\DateTimeInterface $exitDate): static
    {
        $this->exitDate = $exitDate;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->Category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->Category->contains($category)) {
            $this->Category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->Category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Platform>
     */
    public function getPlatform(): Collection
    {
        return $this->platform;
    }

    public function addPlatform(Platform $platform): static
    {
        if (!$this->platform->contains($platform)) {
            $this->platform->add($platform);
        }

        return $this;
    }

    public function removePlatform(Platform $platform): static
    {
        $this->platform->removeElement($platform);

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setGame($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getGame() === $this) {
                $review->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ActivationCode>
     */
    public function getActivationCodes(): Collection
    {
        return $this->activationCodes;
    }

    public function addActivationCode(ActivationCode $activationCode): static
    {
        if (!$this->activationCodes->contains($activationCode)) {
            $this->activationCodes->add($activationCode);
            $activationCode->setGame($this);
        }

        return $this;
    }

    public function removeActivationCode(ActivationCode $activationCode): static
    {
        if ($this->activationCodes->removeElement($activationCode)) {
            // set the owning side to null (unless already changed)
            if ($activationCode->getGame() === $this) {
                $activationCode->setGame(null);
            }
        }

        return $this;
    }
}

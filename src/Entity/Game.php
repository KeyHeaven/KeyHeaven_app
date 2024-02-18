<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;

#[ApiFilter(SearchFilter::class, properties : ['title' => 'ipartial'])]
#[ApiFilter(OrderFilter::class, properties: ['exitDate' => 'DESC'])]
#[ApiResource(normalizationContext: ['groups' => ['get']])]
#[Get]
#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('get')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('get')]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups('get')]
    private ?float $price = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups('get')]
    private ?\DateTimeInterface $exitDate = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'games')]
    #[Groups('get')]
    private Collection $Category;

    #[ORM\ManyToMany(targetEntity: Platform::class, inversedBy: 'games')]
    #[Groups('get')]
    private Collection $platform;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Review::class)]
    #[Groups('get')]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: ActivationCode::class)]
    #[Groups('get')]
    private Collection $activationCodes;

    #[ORM\Column(length: 255)]
    #[Groups('get')]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[Groups('get')]
    private ?Developers $developer = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[Groups('get')]
    private ?Editor $Editor = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[Groups('get')]
    private ?Configuration $Configuration = null;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDeveloper(): ?Developers
    {
        return $this->developer;
    }

    public function setDeveloper(?Developers $developer): static
    {
        $this->developer = $developer;

        return $this;
    }

    public function getEditor(): ?Editor
    {
        return $this->Editor;
    }

    public function setEditor(?Editor $Editor): static
    {
        $this->Editor = $Editor;

        return $this;
    }

    public function getConfiguration(): ?Configuration
    {
        return $this->Configuration;
    }

    public function setConfiguration(?Configuration $Configuration): static
    {
        $this->Configuration = $Configuration;

        return $this;
    }

    public function isInStock(): bool
{
    foreach ($this->getActivationCodes() as $activationCode) {
        if ($activationCode->isAvailable()) {
            return true;
        }
    }

    return false;
}
}

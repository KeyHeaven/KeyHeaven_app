<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registration_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastConnection = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SupportTicket::class)]
    private Collection $supportTickets;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Purchasing::class)]
    private Collection $purchasings;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserInformation::class)]
    private Collection $userInformation;

    public function __construct()
    {
        $this->supportTickets = new ArrayCollection();
        $this->purchasings = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->userInformation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(\DateTimeInterface $registration_date): static
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    public function getLastConnection(): ?\DateTimeInterface
    {
        return $this->lastConnection;
    }

    public function setLastConnection(\DateTimeInterface $lastConnection): static
    {
        $this->lastConnection = $lastConnection;

        return $this;
    }

    /**
     * @return Collection<int, SupportTicket>
     */
    public function getSupportTickets(): Collection
    {
        return $this->supportTickets;
    }

    public function addSupportTicket(SupportTicket $supportTicket): static
    {
        if (!$this->supportTickets->contains($supportTicket)) {
            $this->supportTickets->add($supportTicket);
            $supportTicket->setUser($this);
        }

        return $this;
    }

    public function removeSupportTicket(SupportTicket $supportTicket): static
    {
        if ($this->supportTickets->removeElement($supportTicket)) {
            // set the owning side to null (unless already changed)
            if ($supportTicket->getUser() === $this) {
                $supportTicket->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Purchasing>
     */
    public function getPurchasings(): Collection
    {
        return $this->purchasings;
    }

    public function addPurchasing(Purchasing $purchasing): static
    {
        if (!$this->purchasings->contains($purchasing)) {
            $this->purchasings->add($purchasing);
            $purchasing->setUser($this);
        }

        return $this;
    }

    public function removePurchasing(Purchasing $purchasing): static
    {
        if ($this->purchasings->removeElement($purchasing)) {
            // set the owning side to null (unless already changed)
            if ($purchasing->getUser() === $this) {
                $purchasing->setUser(null);
            }
        }

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
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    public function getUserInformation(): ?UserInformation
    {
        return $this->userInformation;
    }

    public function setUserInformation(?UserInformation $userInformation): static
    {
        $this->userInformation = $userInformation;

        return $this;
    }

    public function addUserInformation(UserInformation $userInformation): static
    {
        if (!$this->userInformation->contains($userInformation)) {
            $this->userInformation->add($userInformation);
            $userInformation->setUser($this);
        }

        return $this;
    }

    public function removeUserInformation(UserInformation $userInformation): static
    {
        if ($this->userInformation->removeElement($userInformation)) {
            // set the owning side to null (unless already changed)
            if ($userInformation->getUser() === $this) {
                $userInformation->setUser(null);
            }
        }

        return $this;
    }
}

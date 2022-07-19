<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\ManyToMany(targetEntity=Member::class, mappedBy="user")
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity=Enrollment::class, mappedBy="User")
     */
    private $enrollments;

    /**
     * @ORM\OneToMany(targetEntity=EnrollmentYoung::class, mappedBy="user")
     */
    private $enrollmentYoungs;

    /**
     * @ORM\OneToMany(targetEntity=EventComment::class, mappedBy="user", orphanRemoval=true)
     */
    private $eventComments;

    /**
     * @ORM\OneToMany(targetEntity=Purchase::class, mappedBy="user")
     * @ORM\OrderBy({"purchasedAt" = "DESC"})
     */
    private $purchases;

    /**
     * @ORM\OneToMany(targetEntity=ShopProduct::class, mappedBy="seller")
     */
    private $shopProducts;

    public const ROLES = [
        'Utilisateur' => 'ROLE_USER',
        'Administrateur' => 'ROLE_ADMIN',
        'Responsable Adhésions' => 'ROLE_ADHESIONS',
        'Président' => 'ROLE_PRESIDENT',
        'Trésorier' => 'ROLE_TRESORIER',
        'Secrétaire' => 'ROLE_SECRETAIRE',
        'Communication' => 'ROLE_COMMUNICATION',
        'Membre du comité' => 'ROLE_COMITE',
        'Responsable tenues' => 'ROLE_TENUES',
    ];

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->enrollments = new ArrayCollection();
        $this->enrollmentYoungs = new ArrayCollection();
        $this->eventComments = new ArrayCollection();
        $this->purchases = new ArrayCollection();
        $this->shopProducts = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Member[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->addUser($this);
        }

        return $this;
    }

    public function removeMember(Member $member): self
    {
        if ($this->members->removeElement($member)) {
            $member->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Enrollment[]
     */
    public function getEnrollments(): Collection
    {
        return $this->enrollments;
    }

    public function addEnrollment(Enrollment $enrollment): self
    {
        if (!$this->enrollments->contains($enrollment)) {
            $this->enrollments[] = $enrollment;
            $enrollment->setUser($this);
        }

        return $this;
    }

    public function removeEnrollment(Enrollment $enrollment): self
    {
        if ($this->enrollments->removeElement($enrollment)) {
            // set the owning side to null (unless already changed)
            if ($enrollment->getUser() === $this) {
                $enrollment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EnrollmentYoung[]
     */
    public function getEnrollmentYoungs(): Collection
    {
        return $this->enrollmentYoungs;
    }

    public function addEnrollmentYoung(EnrollmentYoung $enrollmentYoung): self
    {
        if (!$this->enrollmentYoungs->contains($enrollmentYoung)) {
            $this->enrollmentYoungs[] = $enrollmentYoung;
            $enrollmentYoung->setUser($this);
        }

        return $this;
    }

    public function removeEnrollmentYoung(EnrollmentYoung $enrollmentYoung): self
    {
        if ($this->enrollmentYoungs->removeElement($enrollmentYoung)) {
            // set the owning side to null (unless already changed)
            if ($enrollmentYoung->getUser() === $this) {
                $enrollmentYoung->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EventComment[]
     */
    public function getEventComments(): Collection
    {
        return $this->eventComments;
    }

    public function addEventComments(EventComment $eventComments): self
    {
        if (!$this->eventComments->contains($eventComments)) {
            $this->eventComments[] = $eventComments;
            $eventComments->setUser($this);
        }

        return $this;
    }

    public function removeEventComments(EventComment $eventComments): self
    {
        if ($this->eventComments->removeElement($eventComments)) {
            // set the owning side to null (unless already changed)
            if ($eventComments->getUser() === $this) {
                $eventComments->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Purchase[]
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase): self
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases[] = $purchase;
            $purchase->setUser($this);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): self
    {
        if ($this->purchases->removeElement($purchase)) {
            // set the owning side to null (unless already changed)
            if ($purchase->getUser() === $this) {
                $purchase->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ShopProduct[]
     */
    public function getShopProducts(): Collection
    {
        return $this->shopProducts;
    }

    public function addShopProduct(ShopProduct $shopProduct): self
    {
        if (!$this->shopProducts->contains($shopProduct)) {
            $this->shopProducts[] = $shopProduct;
            $shopProduct->setSeller($this);
        }

        return $this;
    }

    public function removeShopProduct(ShopProduct $shopProduct): self
    {
        if ($this->shopProducts->removeElement($shopProduct)) {
            // set the owning side to null (unless already changed)
            if ($shopProduct->getSeller() === $this) {
                $shopProduct->setSeller(null);
            }
        }

        return $this;
    }
}

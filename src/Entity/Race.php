<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RaceRepository::class)
 */
class Race implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $signInLink;

    /**
     * @ORM\ManyToMany(targetEntity=Member::class, inversedBy="races")
     */
    private $registratedMember;

    /**
     * @ORM\OneToMany(targetEntity=EventComment::class, mappedBy="event", orphanRemoval=true)
     */
    private $eventComments;

    public function __construct()
    {
        $this->registratedMember = new ArrayCollection();
        $this->eventComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getSignInLink(): ?string
    {
        return $this->signInLink;
    }

    public function setSignInLink(?string $signInLink): self
    {
        $this->signInLink = $signInLink;

        return $this;
    }

    /**
     * @return Collection|Member[]
     */
    public function getRegistratedMember(): Collection
    {
        return $this->registratedMember;
    }

    public function addRegistratedMember(Member $registratedMember): self
    {
        if (!$this->registratedMember->contains($registratedMember)) {
            $this->registratedMember[] = $registratedMember;
        }

        return $this;
    }

    public function removeRegistratedMember(Member $registratedMember): self
    {
        $this->registratedMember->removeElement($registratedMember);

        return $this;
    }

    /**
     * @return Collection|EventComment[]
     */
    public function getEventComments(): Collection
    {
        return $this->eventComments;
    }

    public function addEventComment(EventComment $eventComment): self
    {
        if (!$this->eventComments->contains($eventComment)) {
            $this->eventComments[] = $eventComment;
            $eventComment->setEvent($this);
        }

        return $this;
    }

    public function removeEventComment(EventComment $eventComment): self
    {
        if ($this->eventComments->removeElement($eventComment)) {
            // set the owning side to null (unless already changed)
            if ($eventComment->getEvent() === $this) {
                $eventComment->setEvent(null);
            }
        }

        return $this;
    }
}

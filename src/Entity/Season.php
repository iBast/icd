<?php

namespace App\Entity;


use App\Entity\Enrollment;
use App\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SeasonRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=SeasonRepository::class)
 */
class Season implements EntityInterface
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
    private $year;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enrollmentStatus;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $membershipCost;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $swimCost;

    /**
     * @ORM\OneToMany(targetEntity=Enrollment::class, mappedBy="Season")
     */
    private $enrollments;

    /**
     * @ORM\Column(type="boolean")
     */
    private $current;

    public function __construct()
    {
        $this->enrollments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getEnrollmentStatus(): ?bool
    {
        return $this->enrollmentStatus;
    }

    public function setEnrollmentStatus(bool $enrollmentStatus): self
    {
        $this->enrollmentStatus = $enrollmentStatus;

        return $this;
    }

    public function getMembershipCost(): ?int
    {
        return $this->membershipCost;
    }

    public function setMembershipCost(?int $membershipCost): self
    {
        $this->membershipCost = $membershipCost;

        return $this;
    }

    public function getSwimCost(): ?int
    {
        return $this->swimCost;
    }

    public function setSwimCost(?int $swimCost): self
    {
        $this->swimCost = $swimCost;

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
            $enrollment->setSeason($this);
        }

        return $this;
    }

    public function removeEnrollment(Enrollment $enrollment): self
    {
        if ($this->enrollments->removeElement($enrollment)) {
            // set the owning side to null (unless already changed)
            if ($enrollment->getSeason() === $this) {
                $enrollment->setSeason(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->year;
    }

    public function getCurrent(): ?bool
    {
        return $this->current;
    }

    public function setCurrent(bool $current): self
    {
        $this->current = $current;

        return $this;
    }
}

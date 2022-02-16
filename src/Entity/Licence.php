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

use App\Repository\LicenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LicenceRepository::class)
 */
class Licence implements EntityInterface
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
     * @ORM\Column(type="integer")
     */
    private $cost;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=Enrollment::class, mappedBy="Licence")
     */
    private $enrollments;

    /**
     * @ORM\OneToMany(targetEntity=EnrollmentYoung::class, mappedBy="licence")
     */
    private $enrollmentYoungs;

    /**
     * @ORM\Column(type="boolean")
     */
    private $forYoung;

    public function __construct()
    {
        $this->enrollments = new ArrayCollection();
        $this->enrollmentYoungs = new ArrayCollection();
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

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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
            $enrollment->setLicence($this);
        }

        return $this;
    }

    public function removeEnrollment(Enrollment $enrollment): self
    {
        if ($this->enrollments->removeElement($enrollment)) {
            // set the owning side to null (unless already changed)
            if ($enrollment->getLicence() === $this) {
                $enrollment->setLicence(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name.' ('.$this->cost / 100 .' € )';
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
            $enrollmentYoung->setLicence($this);
        }

        return $this;
    }

    public function removeEnrollmentYoung(EnrollmentYoung $enrollmentYoung): self
    {
        if ($this->enrollmentYoungs->removeElement($enrollmentYoung)) {
            // set the owning side to null (unless already changed)
            if ($enrollmentYoung->getLicence() === $this) {
                $enrollmentYoung->setLicence(null);
            }
        }

        return $this;
    }

    public function getForYoung(): ?bool
    {
        return $this->forYoung;
    }

    public function setForYoung(bool $forYoung): self
    {
        $this->forYoung = $forYoung;

        return $this;
    }
}

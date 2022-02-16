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

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MemberRepository::class)
 * @ORM\Table(name="`member`")
 */
class Member implements EntityInterface
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $postCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="members")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Enrollment::class, mappedBy="memberId")
     */
    private $enrollments;

    /**
     * @ORM\OneToMany(targetEntity=EnrollmentYoung::class, mappedBy="owner")
     */
    private $enrollmentYoungs;

    /**
     * @ORM\ManyToMany(targetEntity=Race::class, mappedBy="registratedMember")
     */
    private $races;

    /**
     * @ORM\OneToMany(targetEntity=RaceReport::class, mappedBy="participant")
     */
    private $raceReports;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->enrollments = new ArrayCollection();
        $this->enrollmentYoungs = new ArrayCollection();
        $this->races = new ArrayCollection();
        $this->raceReports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

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
            $enrollment->setMemberId($this);
        }

        return $this;
    }

    public function removeEnrollment(Enrollment $enrollment): self
    {
        if ($this->enrollments->removeElement($enrollment)) {
            // set the owning side to null (unless already changed)
            if ($enrollment->getMemberId() === $this) {
                $enrollment->setMemberId(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->firstName.' '.$this->lastName;
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
            $enrollmentYoung->setOwner($this);
        }

        return $this;
    }

    public function removeEnrollmentYoung(EnrollmentYoung $enrollmentYoung): self
    {
        if ($this->enrollmentYoungs->removeElement($enrollmentYoung)) {
            // set the owning side to null (unless already changed)
            if ($enrollmentYoung->getOwner() === $this) {
                $enrollmentYoung->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Race[]
     */
    public function getRaces(): Collection
    {
        return $this->races;
    }

    public function addRace(Race $race): self
    {
        if (!$this->races->contains($race)) {
            $this->races[] = $race;
            $race->addRegistratedMember($this);
        }

        return $this;
    }

    public function removeRace(Race $race): self
    {
        if ($this->races->removeElement($race)) {
            $race->removeRegistratedMember($this);
        }

        return $this;
    }

    /**
     * @return Collection|RaceReport[]
     */
    public function getRaceReports(): Collection
    {
        return $this->raceReports;
    }

    public function addRaceReport(RaceReport $raceReport): self
    {
        if (!$this->raceReports->contains($raceReport)) {
            $this->raceReports[] = $raceReport;
            $raceReport->setParticipant($this);
        }

        return $this;
    }

    public function removeRaceReport(RaceReport $raceReport): self
    {
        if ($this->raceReports->removeElement($raceReport)) {
            // set the owning side to null (unless already changed)
            if ($raceReport->getParticipant() === $this) {
                $raceReport->setParticipant(null);
            }
        }

        return $this;
    }
}

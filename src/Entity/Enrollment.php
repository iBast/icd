<?php

namespace App\Entity;

use App\Entity\Member;
use App\Entity\Season;
use App\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EnrollmentRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=EnrollmentRepository::class)
 * @Vich\Uploadable
 */
class Enrollment implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="enrollments")
     * @ORM\JoinColumn(nullable=true, name="season_id")
     */
    private $Season;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="enrollments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $memberId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalAmount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paymentMethod;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $paymentAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $endedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMember;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasPoolAcces;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasCareAuthorization;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasPhotoAuthorization;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasLeaveAloneAuthorization;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasTreatment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $treatmentDetails;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasAllergy;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $AllergyDetails;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emergencyContact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emergencyPhone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $medicalAuthPath;

    /**
     * @Vich\UploadableField(mapping="enrollment_docs", fileNameProperty="medicalAuthPath")
     * 
     * @var File|null
     */
    private $medicalFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $FFTriDocPath;

    /**
     * @Vich\UploadableField(mapping="enrollment_docs", fileNameProperty="FFTriDocPath")
     * 
     * @var File|null
     */
    private $FFTriDocFile;

    /**
     * @ORM\ManyToOne(targetEntity=Licence::class, inversedBy="enrollments")
     */
    private $Licence;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="enrollments")
     */
    private $User;

    public const STATUS = [
        'Dossier crée' => 'Dossier crée',
        'En Attente de validation' => 'En Attente de validation',
        'En Attente de la FFTri' => 'En Attente de la FFTri',
        'Dossier validé' => 'Dossier Validé'
    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): ?Season
    {
        return $this->Season;
    }

    public function setSeason(?Season $Season): self
    {
        $this->Season = $Season;

        return $this;
    }

    public function getMemberId(): ?Member
    {
        return $this->memberId;
    }

    public function setMemberId(?Member $memberId): self
    {
        $this->memberId = $memberId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(?int $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getPaymentAt(): ?\DateTimeImmutable
    {
        return $this->paymentAt;
    }

    public function setPaymentAt(?\DateTimeImmutable $paymentAt): self
    {
        $this->paymentAt = $paymentAt;

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

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeImmutable $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getIsMember(): ?bool
    {
        return $this->isMember;
    }

    public function setIsMember(bool $isMember): self
    {
        $this->isMember = $isMember;

        return $this;
    }

    public function getHasPoolAcces(): ?bool
    {
        return $this->hasPoolAcces;
    }

    public function setHasPoolAcces(bool $hasPoolAcces): self
    {
        $this->hasPoolAcces = $hasPoolAcces;

        return $this;
    }

    public function getHasCareAuthorization(): ?bool
    {
        return $this->hasCareAuthorization;
    }

    public function setHasCareAuthorization(bool $hasCareAuthorization): self
    {
        $this->hasCareAuthorization = $hasCareAuthorization;

        return $this;
    }

    public function getHasPhotoAuthorization(): ?bool
    {
        return $this->hasPhotoAuthorization;
    }

    public function setHasPhotoAuthorization(bool $hasPhotoAuthorization): self
    {
        $this->hasPhotoAuthorization = $hasPhotoAuthorization;

        return $this;
    }

    public function getHasLeaveAloneAuthorization(): ?bool
    {
        return $this->hasLeaveAloneAuthorization;
    }

    public function setHasLeaveAloneAuthorization(bool $hasLeaveAloneAuthorization): self
    {
        $this->hasLeaveAloneAuthorization = $hasLeaveAloneAuthorization;

        return $this;
    }

    public function getHasTreatment(): ?bool
    {
        return $this->hasTreatment;
    }

    public function setHasTreatment(bool $hasTreatment): self
    {
        $this->hasTreatment = $hasTreatment;

        return $this;
    }

    public function getTreatmentDetails(): ?string
    {
        return $this->treatmentDetails;
    }

    public function setTreatmentDetails(?string $treatmentDetails): self
    {
        $this->treatmentDetails = $treatmentDetails;

        return $this;
    }

    public function getHasAllergy(): ?bool
    {
        return $this->hasAllergy;
    }

    public function setHasAllergy(bool $hasAllergy): self
    {
        $this->hasAllergy = $hasAllergy;

        return $this;
    }

    public function getAllergyDetails(): ?string
    {
        return $this->AllergyDetails;
    }

    public function setAllergyDetails(?string $AllergyDetails): self
    {
        $this->AllergyDetails = $AllergyDetails;

        return $this;
    }

    public function getEmergencyContact(): ?string
    {
        return $this->emergencyContact;
    }

    public function setEmergencyContact(?string $emergencyContact): self
    {
        $this->emergencyContact = $emergencyContact;

        return $this;
    }

    public function getEmergencyPhone(): ?string
    {
        return $this->emergencyPhone;
    }

    public function setEmergencyPhone(?string $emergencyPhone): self
    {
        $this->emergencyPhone = $emergencyPhone;

        return $this;
    }

    public function getMedicalAuthPath()
    {
        return $this->medicalAuthPath;
    }

    public function setMedicalAuthPath($medicalAuthPath): self
    {
        $this->medicalAuthPath = $medicalAuthPath;

        return $this;
    }

    public function getFFTriDocPath()
    {
        return $this->FFTriDocPath;
    }

    public function setFFTriDocPath($FFTriDocPath): self
    {
        $this->FFTriDocPath = $FFTriDocPath;

        return $this;
    }

    public function __toString()
    {
        return $this->getMemberId()->getFirstName() . ' ' . $this->getMemberId()->getLastName() . ' - ' . $this->getStatus();
    }

    public function getLicence(): ?Licence
    {
        return $this->Licence;
    }

    public function setLicence(?Licence $Licence): self
    {
        $this->Licence = $Licence;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $medicalFile
     */
    public function setMedicalFile(?File $medicalFile = null): void
    {
        $this->medicalFile = $medicalFile;

        if (null !== $medicalFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getMedicalFile(): ?File
    {
        return $this->medicalFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $FFTriDocFile
     */
    public function setFFTriDocFile(?File $FFTriDocFile = null): void
    {
        $this->FFTriDocFile = $FFTriDocFile;

        if (null !== $FFTriDocFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getFFTriDocFile(): ?File
    {
        return $this->FFTriDocFile;
    }
}

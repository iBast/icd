<?php

namespace App\Entity;

use App\Helper\ParamsInService;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EnrollmentYoungRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=EnrollmentYoungRepository::class)
 *  @Vich\Uploadable
 */
class EnrollmentYoung implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="enrollmentYoungs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="enrollmentYoungs")
     */
    private $season;

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
    private $allergyDetails;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $FFTriDocPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $antiDopingPath;

    /**
     * @ORM\ManyToOne(targetEntity=Licence::class, inversedBy="enrollmentYoungs")
     */
    private $licence;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="enrollmentYoungs")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDocsValid = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasPhotoAuthorization = false;

    /**
     * @Vich\UploadableField(mapping="enrollment_docs", fileNameProperty="FFTriDocPath")
     * 
     * @var File|null
     */
    private $FFTriDocFile;

    /**
     * @Vich\UploadableField(mapping="enrollment_docs", fileNameProperty="medicalAuthPath")
     * 
     * @var File|null
     */
    private $medicalFile;

    /**
     * @Vich\UploadableField(mapping="enrollment_docs", fileNameProperty="antiDopingPath")
     * 
     * @var File|null
     */
    private $antiDopingFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $FFTriDoc2Path;

    /**
     * @Vich\UploadableField(mapping="enrollment_docs", fileNameProperty="FFTriDoc2Path")
     * 
     * @var File|null
     */
    private $FFTriDoc2File;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?Member
    {
        return $this->owner;
    }

    public function setOwner(?Member $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

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

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
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
        return $this->allergyDetails;
    }

    public function setAllergyDetails(string $allergyDetails): self
    {
        $this->allergyDetails = $allergyDetails;

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

    public function getMedicalAuthPath(): ?string
    {
        return $this->medicalAuthPath;
    }

    public function setMedicalAuthPath(?string $medicalAuthPath): self
    {
        $this->medicalAuthPath = $medicalAuthPath;

        return $this;
    }

    public function getFFTriDocPath(): ?string
    {
        return $this->FFTriDocPath;
    }

    public function setFFTriDocPath(?string $FFTriDocPath): self
    {
        $this->FFTriDocPath = $FFTriDocPath;

        return $this;
    }

    public function getAntiDopingPath(): ?string
    {
        return $this->antiDopingPath;
    }

    public function setAntiDopingPath(?string $antiDopingPath): self
    {
        $this->antiDopingPath = $antiDopingPath;

        return $this;
    }

    public function getLicence(): ?Licence
    {
        return $this->licence;
    }

    public function setLicence(?Licence $licence): self
    {
        $this->licence = $licence;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIsDocsValid(): ?bool
    {
        return $this->isDocsValid;
    }

    public function setIsDocsValid(bool $isDocsValid): self
    {
        $this->isDocsValid = $isDocsValid;

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
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $antiDopingFile
     */
    public function setAntiDopingFile(?File $antiDopingFile = null): void
    {
        $this->antiDopingFile = $antiDopingFile;

        if (null !== $antiDopingFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getAntiDopingFile(): ?File
    {
        return $this->antiDopingFile;
    }

    public function getFFTriDoc2Path(): ?string
    {
        return $this->FFTriDoc2Path;
    }

    public function setFFTriDoc2Path(?string $FFTriDoc2Path): self
    {
        $this->FFTriDoc2Path = $FFTriDoc2Path;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $FFTriDoc2File
     */
    public function setFFTriDoc2File(?File $FFTriDoc2File = null): void
    {
        $this->FFTriDoc2File = $FFTriDoc2File;

        if (null !== $FFTriDoc2File) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getFFTriDoc2File(): ?File
    {
        return $this->FFTriDoc2File;
    }

    public function checkPayment()
    {
        if ($this->paymentAt != null) {
            return false;
        }
        return true;
    }

    public function checkDocuments()
    {
        if ($this->isDocsValid == true) {
            return false;
        }
        return true;
    }

    public function checkEmail()
    {
        if ($this->isDocsValid == true && $this->paymentAt != null) {
            return false;
        }
        return true;
    }

    public function checkFinalValidation()
    {
        if ($this->isDocsValid == true && $this->paymentAt != null && $this->endedAt == null) {
            return true;
        }
        return false;
    }

    public function __toString()
    {
        return $this->owner->getFirstName() . ' ' . $this->owner->getLastName();
    }
}

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

use App\Repository\AccountingDocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=AccountingDocumentRepository::class)
 * @Vich\Uploadable
 */
class AccountingDocument implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @Vich\UploadableField(mapping="accountingFile", fileNameProperty="path")
     *
     * @var File|null
     */
    private $accountingFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalAmount;

    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity=Accounting::class, inversedBy="accountingDocuments")
     */
    private $accounting;

    public function __construct()
    {
        $this->accounting = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $accountingFile
     */
    public function setAccountingFile(?File $accountingFile = null): void
    {
        $this->accountingFile = $accountingFile;

        if (null !== $accountingFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getAccountingFile(): ?File
    {
        return $this->accountingFile;
    }

    public function __toString()
    {
        return $this->id.' - '.$this->totalAmount / 100 .' €';
    }

    /**
     * @return Collection|Accounting[]
     */
    public function getAccounting(): Collection
    {
        return $this->accounting;
    }

    public function addAccounting(Accounting $accounting): self
    {
        if (!$this->accounting->contains($accounting)) {
            $this->accounting[] = $accounting;
        }

        return $this;
    }

    public function removeAccounting(Accounting $accounting): self
    {
        $this->accounting->removeElement($accounting);

        return $this;
    }
}

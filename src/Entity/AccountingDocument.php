<?php

namespace App\Entity;

use App\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AccountingDocumentRepository;

/**
 * @ORM\Entity(repositoryClass=AccountingDocumentRepository::class)
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
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $totalAmount;

    /**
     * @ORM\OneToOne(targetEntity=Accounting::class, mappedBy="document", cascade={"persist", "remove"})
     */
    private $accounting;

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

    public function getAccounting(): ?Accounting
    {
        return $this->accounting;
    }

    public function setAccounting(?Accounting $accounting): self
    {
        // unset the owning side of the relation if necessary
        if ($accounting === null && $this->accounting !== null) {
            $this->accounting->setDocument(null);
        }

        // set the owning side of the relation if necessary
        if ($accounting !== null && $accounting->getDocument() !== $this) {
            $accounting->setDocument($this);
        }

        $this->accounting = $accounting;

        return $this;
    }
}

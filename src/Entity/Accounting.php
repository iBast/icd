<?php

namespace App\Entity;

use App\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AccountingRepository;

/**
 * @ORM\Entity(repositoryClass=AccountingRepository::class)
 */
class Accounting implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;

    /**
     * @ORM\OneToOne(targetEntity=AccountingDocument::class, inversedBy="accounting", cascade={"persist", "remove"})
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="accountings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wording;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $debit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $credit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDocument(): ?AccountingDocument
    {
        return $this->document;
    }

    public function setDocument(?AccountingDocument $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(?string $wording): self
    {
        $this->wording = $wording;

        return $this;
    }

    public function getDebit(): ?int
    {
        return $this->debit;
    }

    public function setDebit(?int $debit): self
    {
        $this->debit = $debit;

        return $this;
    }

    public function getCredit(): ?int
    {
        return $this->credit;
    }

    public function setCredit(?int $credit): self
    {
        $this->credit = $credit;

        return $this;
    }

    public function __toString()
    {
        return $this->getDate()->format('d/m/Y') . ' ' . $this->getWording() . ' ' . $this->getDebit() / 100 . ' ' . $this->getCredit() / 100;
    }
}

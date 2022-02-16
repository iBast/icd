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

use App\Repository\AccountingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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

    /**
     * @ORM\ManyToMany(targetEntity=AccountingDocument::class, mappedBy="accounting")
     */
    private $accountingDocuments;

    public function __construct()
    {
        $this->accountingDocuments = new ArrayCollection();
    }

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
        return $this->getDate()->format('d/m/Y').' '.$this->getWording().' '.$this->getDebit() / 100 .' '.$this->getCredit() / 100;
    }

    /**
     * @return Collection|AccountingDocument[]
     */
    public function getAccountingDocuments(): Collection
    {
        return $this->accountingDocuments;
    }

    public function addAccountingDocument(AccountingDocument $accountingDocument): self
    {
        if (!$this->accountingDocuments->contains($accountingDocument)) {
            $this->accountingDocuments[] = $accountingDocument;
            $accountingDocument->addAccounting($this);
        }

        return $this;
    }

    public function removeAccountingDocument(AccountingDocument $accountingDocument): self
    {
        if ($this->accountingDocuments->removeElement($accountingDocument)) {
            $accountingDocument->removeAccounting($this);
        }

        return $this;
    }
}

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

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 */
class Invoice implements EntityInterface
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $toName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $toAdress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $toPostCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $toCity;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalAmount;

    /**
     * @ORM\OneToMany(targetEntity=InvoiceLine::class, mappedBy="invoice", orphanRemoval=true)
     */
    private $invoiceLines;

    public function __construct()
    {
        $this->invoiceLines = new ArrayCollection();
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

    public function getToName(): ?string
    {
        return $this->toName;
    }

    public function setToName(?string $toName): self
    {
        $this->toName = $toName;

        return $this;
    }

    public function getToAdress(): ?string
    {
        return $this->toAdress;
    }

    public function setToAdress(?string $toAdress): self
    {
        $this->toAdress = $toAdress;

        return $this;
    }

    public function getToPostCode(): ?string
    {
        return $this->toPostCode;
    }

    public function setToPostCode(?string $toPostCode): self
    {
        $this->toPostCode = $toPostCode;

        return $this;
    }

    public function getToCity(): ?string
    {
        return $this->toCity;
    }

    public function setToCity(?string $toCity): self
    {
        $this->toCity = $toCity;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * @return Collection|InvoiceLine[]
     */
    public function getInvoiceLines(): Collection
    {
        return $this->invoiceLines;
    }

    public function addInvoiceLine(InvoiceLine $invoiceLine): self
    {
        if (!$this->invoiceLines->contains($invoiceLine)) {
            $this->invoiceLines[] = $invoiceLine;
            $invoiceLine->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceLine(InvoiceLine $invoiceLine): self
    {
        if ($this->invoiceLines->removeElement($invoiceLine)) {
            // set the owning side to null (unless already changed)
            if ($invoiceLine->getInvoice() === $this) {
                $invoiceLine->setInvoice(null);
            }
        }

        return $this;
    }
}

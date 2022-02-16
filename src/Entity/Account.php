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

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Accounting::class, mappedBy="account")
     */
    private $accountings;

    public function __construct()
    {
        $this->accountings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
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

    /**
     * @return Collection|Accounting[]
     */
    public function getAccountings(): Collection
    {
        return $this->accountings;
    }

    public function addAccounting(Accounting $accounting): self
    {
        if (!$this->accountings->contains($accounting)) {
            $this->accountings[] = $accounting;
            $accounting->setAccount($this);
        }

        return $this;
    }

    public function removeAccounting(Accounting $accounting): self
    {
        if ($this->accountings->removeElement($accounting)) {
            // set the owning side to null (unless already changed)
            if ($accounting->getAccount() === $this) {
                $accounting->setAccount(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNumber().' '.$this->getName();
    }

    public function getDebit()
    {
        $debit = 0;

        foreach ($this->getAccountings() as $line) {
            $debit += $line->getDebit();
        }

        return $debit;
    }

    public function getCredit()
    {
        $credit = 0;

        foreach ($this->getAccountings() as $line) {
            $credit += $line->getCredit();
        }

        return $credit;
    }

    public function getDebitSolde()
    {
        return $this->getDebit() - $this->getCredit();
    }

    public function getCreditSolde()
    {
        return $this->getCredit() - $this->getDebit();
    }
}

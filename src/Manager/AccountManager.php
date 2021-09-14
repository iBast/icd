<?php

namespace App\Manager;

use DateTimeImmutable;
use App\Entity\Account;
use App\Entity\Accounting;
use App\Entity\EntityInterface;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;

class AccountManager extends AbstractManager
{
    protected $accountRepository;
    protected $em;

    public function __construct(AccountRepository $accountRepository, EntityManagerInterface $em)
    {
        $this->accountRepository = $accountRepository;
        parent::__construct($em);
    }
    public function initialise(EntityInterface $entity)
    {
        //interface
    }

    public function createAccount(int $number, string $name)
    {
        if ($this->accountRepository->findOneBy(['number' => $number]) == null) {
            $account = new Account;
            $account->setName($name)->setNumber($number);
            $this->save($account);
        }
    }

    public function debit(Account $account, $wording, $amount, $date = null)
    {
        $date = ($date == null) ? new DateTimeImmutable() : $date;
        $entry = new Accounting;
        $entry->setAccount($account)
            ->setWording($wording)
            ->setDebit($amount)
            ->setDate($date);
        $this->save($entry);
    }

    public function credit(Account $account, $wording, $amount, $date = null)
    {
        $date = ($date == null) ? new DateTimeImmutable() : $date;
        $entry = new Accounting;
        $entry->setAccount($account)
            ->setWording($wording)
            ->setCredit($amount)
            ->setDate($date);
        $this->save($entry);
    }
}

<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Manager;

use App\Entity\Account;
use App\Entity\Accounting;
use App\Entity\EntityInterface;
use App\Repository\AccountRepository;
use DateTimeImmutable;
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
        if (null === $this->accountRepository->findOneBy(['number' => $number])) {
            $account = new Account();
            $account->setName($name)->setNumber($number);
            $this->save($account);
        }
    }

    public function debit(Account $account, $wording, $amount, $date)
    {
        $entry = new Accounting();
        $entry->setAccount($account)
            ->setWording($wording)
            ->setDebit($amount)
            ->setDate($date);
        $this->save($entry);
    }

    public function credit(Account $account, $wording, $amount, $date)
    {
        $entry = new Accounting();
        $entry->setAccount($account)
            ->setWording($wording)
            ->setCredit($amount)
            ->setDate($date);
        $this->save($entry);
    }

    public function newEntry($debitAccount, $creditAccount, $wording, $amount, $date = null)
    {
        $debit = $this->accountRepository->findOneBy(['number' => $debitAccount]);
        $credit = $this->accountRepository->findOneBy(['number' => $creditAccount]);
        $date = (null === $date) ? new DateTimeImmutable() : $date;
        $this->debit($debit, $wording, $amount, $date);
        $this->credit($credit, $wording, $amount, $date);
    }
}

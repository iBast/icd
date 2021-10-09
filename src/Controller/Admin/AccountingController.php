<?php

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountingController extends AbstractController
{
    private $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    #[Route('/admin/tresorerie/comptes', name: 'admin_accounting_accounts')]
    public function accounts()
    {
        return $this->render('admin/accounting/accounts.html.twig', [
            'accounts' => $this->accountRepository->findAll()
        ]);
    }

    #[Route('/admin/tresorerie/compte/{number}', name: 'admin_accounting_account')]
    public function account(Account $account)
    {
        return $this->render('admin/accounting/account.html.twig', [
            'account' => $account
        ]);
    }

    #[Route('/admin/tresorerie/reslutats', name: 'admin_accounting_results')]
    public function results()
    {
        $products = $this->accountRepository->getProducts();
        $charges = $this->accountRepository->getCharges();

        $totalProduct = 0;
        $totalCharge = 0;

        foreach ($products as $product) {
            $totalProduct += $product->getCreditSolde();
        }

        foreach ($charges as $charge) {
            $totalCharge += $charge->getCreditSolde();
        }

        return $this->render('admin/accounting/results.html.twig', [
            'products' => $products,
            'charges' => $charges,
            'totalcharge' => $totalCharge,
            'totalproduct' => $totalProduct
        ]);
    }

    #[Route('/admin/tresorerie/balance', name: 'admin_accounting_balance')]
    public function balance()
    {
        $accounts = $this->accountRepository->findBy([], ['number' => 'asc']);
        $debit = 0;
        $credit = 0;

        foreach ($accounts as $account) {
            $debit += $account->getDebit();
            $credit += $account->getCredit();
        }

        return $this->render('admin/accounting/balance.html.twig', [
            'accounts' => $accounts,
            'debit' => $debit,
            'credit' => $credit
        ]);
    }
}

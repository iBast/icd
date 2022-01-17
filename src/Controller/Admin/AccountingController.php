<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Account;
use App\Form\DatePickerType;
use App\Repository\AccountRepository;
use App\Repository\AccountingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountingController extends AbstractController
{
    private $accountRepository;
    private $accountingRepository;

    public function __construct(AccountRepository $accountRepository, AccountingRepository $accountingRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->accountingRepository = $accountingRepository;
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
            $totalCharge += $charge->getDebitSolde();
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

    #[Route('admin/tresorerie/bilan', name: 'admin_accounting_bilan')]
    public function bilan(Request $request, $begining = null, $end = null)
    {
        if ($begining === null) {
            $date = (new DateTime());
            $date->setDate(date("Y", $date->getTimestamp()), 11, 1)->setTime(0, 0, 0);
            $begining = date('Y-m-d H:i:s', strtotime('-1 year', $date->getTimestamp()));
        }
        if ($end === null) {
            $date = (new DateTime())->setDate(date("Y", $date->getTimestamp()), 10, 31);
            $end = date('Y-m-d H:i:s', $date->getTimestamp());
        }

        $form = $this->createForm(DatePickerType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            $begining = date('Y-m-d H:i:s', $datas['begining']->getTimestamp());
            $end = date('Y-m-d H:i:s', $datas['end']->getTimestamp());
        }

        $accounts = $this->accountingRepository->findByDates($begining, $end);
        $soldeadherents = 0;
        $soldebanque = 0;
        $soldefournisseurs = 0;
        $resultat = 0;
        $produits = 0;
        $charges = 0;
        $soldereports = 0;
        $soldeproduitavance = 0;
        $soldechargeavance = 0;

        foreach ($accounts as $account) {
            if (substr($account->getAccount(), 0, 2) == '41') {
                $soldeadherents += $account->getDebit() - $account->getCredit();
            }
            if (substr($account->getAccount(), 0, 1) == '5') {
                $soldebanque += $account->getDebit() - $account->getCredit();
            }
            if (substr($account->getAccount(), 0, 3) == '401') {
                $soldefournisseurs += $account->getCredit() - $account->getDebit();
            }
            if (substr($account->getAccount(), 0, 1) == '7') {
                $produits += $account->getCredit() - $account->getDebit();
            }
            if (substr($account->getAccount(), 0, 1) == '6') {
                $charges += $account->getDebit() - $account->getCredit();
            }
            if (substr($account->getAccount(), 0, 1) == '1') {
                $soldereports += $account->getCredit() - $account->getDebit();
            }
            if (substr($account->getAccount(), 0, 3) == '487') {
                $soldeproduitavance += $account->getCredit() - $account->getDebit();
            }
            if (substr($account->getAccount(), 0, 3) == '486') {
                $soldechargeavance += $account->getDebit() - $account->getCredit();
            }
        }

        $resultat = $produits - $charges;

        return $this->render('admin/accounting/bilan.html.twig', [
            'adherents' => $soldeadherents,
            'banque' => $soldebanque,
            'fournisseurs' => $soldefournisseurs,
            'resultat' => $resultat,
            'reports' => $soldereports,
            'PCA' => $soldeproduitavance,
            'CCA' => $soldechargeavance,
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/tresorerie/operations', name: 'admin_accounting_operations')]
    public function operations()
    {
        return $this->render('admin/accounting/operations.html.twig');
    }

    #[Route('/admin/tresorerie/operations', name: 'admin_accounting_endOfExercice')]
    public function endOfExercice()
    {
    }
}

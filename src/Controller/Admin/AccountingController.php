<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Form\DatePickerType;
use App\Repository\AccountingRepository;
use App\Repository\AccountRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
            'accounts' => $this->accountRepository->findAll(),
        ]);
    }

    #[Route('/admin/tresorerie/compte/{number}', name: 'admin_accounting_account')]
    public function account(Account $account)
    {
        return $this->render('admin/accounting/account.html.twig', [
            'account' => $account,
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
            'totalproduct' => $totalProduct,
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
            'credit' => $credit,
        ]);
    }

    #[Route('admin/tresorerie/bilan', name: 'admin_accounting_bilan')]
    public function bilan(Request $request, $begining = null, $end = null)
    {
        if (null === $begining) {
            $date = (new DateTime());
            $date->setDate(date('Y', $date->getTimestamp()), 11, 1)->setTime(0, 0, 0);
            $begining = date('Y-m-d H:i:s', strtotime('-1 year', $date->getTimestamp()));
        }
        if (null === $end) {
            $date = (new DateTime())->setDate(date('Y', $date->getTimestamp()), 10, 31);
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
            if ('41' === mb_substr($account->getAccount(), 0, 2)) {
                $soldeadherents += $account->getDebit() - $account->getCredit();
            }
            if ('5' === mb_substr($account->getAccount(), 0, 1)) {
                $soldebanque += $account->getDebit() - $account->getCredit();
            }
            if ('401' === mb_substr($account->getAccount(), 0, 3)) {
                $soldefournisseurs += $account->getCredit() - $account->getDebit();
            }
            if ('7' === mb_substr($account->getAccount(), 0, 1)) {
                $produits += $account->getCredit() - $account->getDebit();
            }
            if ('6' === mb_substr($account->getAccount(), 0, 1)) {
                $charges += $account->getDebit() - $account->getCredit();
            }
            if ('1' === mb_substr($account->getAccount(), 0, 1)) {
                $soldereports += $account->getCredit() - $account->getDebit();
            }
            if ('487' === mb_substr($account->getAccount(), 0, 3)) {
                $soldeproduitavance += $account->getCredit() - $account->getDebit();
            }
            if ('486' === mb_substr($account->getAccount(), 0, 3)) {
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
            'form' => $form->createView(),
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

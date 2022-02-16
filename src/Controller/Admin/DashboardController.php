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
use App\Entity\Accounting;
use App\Entity\AccountingDocument;
use App\Entity\Enrollment;
use App\Entity\EnrollmentYoung;
use App\Entity\Invoice;
use App\Entity\Licence;
use App\Entity\Member;
use App\Entity\Purchase;
use App\Entity\Season;
use App\Entity\ShopCategory;
use App\Entity\ShopProduct;
use App\Entity\ShopProductVariant;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dashboard')
            ->setTranslationDomain('admin');
    }

    //'Season' => $season->getId()
    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::section('Saisons'),
            MenuItem::linkToCrud('Saisons', 'fas fa-list', Season::class),
            MenuItem::linkToCrud('Licences', 'fas fa-id-badge', Licence::class),
            MenuItem::linkToCrud('Membres', 'fas fa-users', Member::class),
            MenuItem::linkToCrud('Adhésions', 'fas fa-folder-open', Enrollment::class),
            MenuItem::linkToCrud('Adhésions Jeunes', 'fas fa-folder-open', EnrollmentYoung::class),
            MenuItem::section('Boutique')->setPermission('ROLE_TENUES'),
            MenuItem::linkToCrud('Catégories', 'fas fa-list', ShopCategory::class)->setPermission('ROLE_TENUES'),
            MenuItem::linkToCrud('Produits', 'fas fa-tshirt', ShopProduct::class)->setPermission('ROLE_TENUES'),
            MenuItem::linkToCrud('Variation produits', 'fas fa-box-open', ShopProductVariant::class)->setPermission('ROLE_TENUES'),
            MenuItem::linkToCrud('Commandes', 'fas fa-box-open', Purchase::class)->setPermission('ROLE_TENUES'),
            MenuItem::linkToRoute('Liste des produits commandés', 'fas fa-list', 'admin_purchase_ordered_products')->setPermission('ROLE_TENUES'),
            //MenuItem::section('Trésorerie')->setPermission('ROLE_TRESORIER'),
            //MenuItem::linkToCrud('Comptes', 'fas fa-list', Account::class)->setPermission('ROLE_TRESORIER'),
            //MenuItem::linkToCrud('Journal', 'fas fa-newspaper', Accounting::class)->setPermission('ROLE_TRESORIER'),
            //MenuItem::linkToCrud('Pièces', 'fas fa-file', AccountingDocument::class)->setPermission('ROLE_TRESORIER'),
            //MenuItem::linkToCrud('Factures', 'fas fa-file-invoice', Invoice::class)->setPermission('ROLE_TRESORIER'),
            //MenuItem::linkToRoute('Comptes', 'fas fa-file-invoice-dollar', 'admin_accounting_accounts')->setPermission('ROLE_TRESORIER'),
            //MenuItem::linkToRoute('Compte de résultat', 'fas fa-file-invoice-dollar', 'admin_accounting_results')->setPermission('ROLE_TRESORIER'),
            //MenuItem::linkToRoute('Balance', 'fas fa-file-invoice-dollar', 'admin_accounting_balance')->setPermission('ROLE_TRESORIER'),
            //MenuItem::linkToRoute('Bilan', 'fas fa-file-invoice-dollar', 'admin_accounting_bilan')->setPermission('ROLE_TRESORIER'),
            //MenuItem::linkToRoute('Opérations', 'fas fa-cogs', 'admin_accounting_operations')->setPermission('ROLE_TRESORIER'),
            MenuItem::section('Gestion du site')->setPermission('ROLE_ADMIN'),
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class)->setPermission('ROLE_ADMIN'),
            MenuItem::section('Ressources'),
            MenuItem::linkToUrl('EspaceTri', 'fas fa-swimmer', 'https://espacetri.fftri.com')->setLinkRel('rel="opener"'),
            MenuItem::linkToUrl('Blog', 'fas fa-biking', 'https://ironclub.blog')->setLinkRel('opener'),
            MenuItem::linkToRoute('Retour au site', 'fas fa-home', 'home'),
        ];
    }
}

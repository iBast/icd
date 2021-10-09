<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Member;
use App\Entity\Season;
use App\Entity\Account;
use App\Entity\Invoice;
use App\Entity\Licence;
use App\Entity\Accounting;
use App\Entity\Enrollment;
use App\Entity\EnrollmentYoung;
use App\Entity\AccountingDocument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use App\Repository\SeasonRepository;

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
            ->setTranslationDomain('admin');;
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
            MenuItem::section('Trésorerie')->setPermission('ROLE_TRESORIER'),
            MenuItem::linkToCrud('Comptes', 'fas fa-list', Account::class)->setPermission('ROLE_TRESORIER'),
            MenuItem::linkToCrud('Journal', 'fas fa-newspaper', Accounting::class)->setPermission('ROLE_TRESORIER'),
            MenuItem::linkToCrud('Pièces', 'fas fa-file', AccountingDocument::class)->setPermission('ROLE_TRESORIER'),
            MenuItem::linkToCrud('Factures', 'fas fa-file-invoice', Invoice::class)->setPermission('ROLE_TRESORIER'),
            MenuItem::linkToRoute('Comptes', 'fas fa-file-invoice-dollar', 'admin_accounting_accounts')->setPermission('ROLE_TRESORIER'),
            MenuItem::linkToRoute('Compte de résultat', 'fas fa-file-invoice-dollar', 'admin_accounting_results')->setPermission('ROLE_TRESORIER'),
            MenuItem::linkToRoute('Balance', 'fas fa-file-invoice-dollar', 'admin_accounting_balance')->setPermission('ROLE_TRESORIER'),
            MenuItem::section('Gestion du site')->setPermission('ROLE_ADMIN'),
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class)->setPermission('ROLE_ADMIN'),
            MenuItem::section('Ressources'),
            MenuItem::linkToUrl('EspaceTri', 'fas fa-swimmer', 'https://espacetri.fftri.com')->setLinkRel('rel="opener"'),
            MenuItem::linkToUrl('Blog', 'fas fa-biking', 'https://ironclub.blog')->setLinkRel('opener'),
            MenuItem::linkToRoute('Retour au site', 'fas fa-home', 'home')
        ];
    }
}

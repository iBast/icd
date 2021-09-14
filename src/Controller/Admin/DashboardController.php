<?php

namespace App\Controller\Admin;

use App\Entity\Member;
use App\Entity\Season;
use App\Entity\Account;
use App\Entity\Accounting;
use App\Entity\AccountingDocument;
use App\Entity\Enrollment;
use App\Entity\Licence;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Menu\SubMenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

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
            ->setTitle('Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Gestion des saisons', 'fas fa-folder-open')->setSubItems([
            MenuItem::linkToCrud('Saisons', 'fas fa-list', Season::class),
            MenuItem::linkToCrud('Licences', 'fas fa-id-badge', Licence::class),
            MenuItem::linkToCrud('Membres', 'fas fa-users', Member::class),
            MenuItem::linkToCrud('Adhésions', 'fas fa-folder-open', Enrollment::class)

        ]);
        yield MenuItem::subMenu('Tenues', 'fas fa-tshirt');
        yield MenuItem::subMenu('Evènements', 'fas fa-calendar-alt');
        yield MenuItem::subMenu('Trésorerie', 'fas fa-coins')->setSubItems([
            MenuItem::linkToCrud('Comptes', 'fas fa-list', Account::class),
            MenuItem::linkToCrud('Journal', 'fas fa-newspaper', Accounting::class),
            MenuItem::linkToCrud('Pièces', 'fas fa-file', AccountingDocument::class)
        ]);
        yield MenuItem::subMenu('Gestion du site', 'fas fa-cog');
        yield MenuItem::linkToRoute('Retour au site', 'fas fa-home', 'home');
    }
}

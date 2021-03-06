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

use App\Entity\Purchase;
use App\Manager\PurchaseManager;
use Doctrine\ORM\Mapping\Entity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class PurchaseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Purchase::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
            ->showEntityActionsInlined()
            ->setDefaultSort(['purchasedAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $acceptOrder = Action::new('acceptOrder', 'Accepter la commande', 'fas fa-check')
            ->linkToCrudAction('acceptOrder')->setCssClass('btn btn-link')->displayIf(fn ($entity) => $entity->checkStatus(Purchase::STATUS_CREATED));
        $confirmPayment = Action::new('confirmPayment', 'Confirmer le paiement', 'fas fa-euro-sign')
            ->linkToCrudAction('confirmPayment')->setCssClass('btn btn-link')->displayIf(fn ($entity) => $entity->checkStatus(Purchase::STATUS_ACCEPTED));
        $deliverOrder = Action::new('deliverOrder', 'Confirmer la livraison', 'fas fa-check')
            ->linkToCrudAction('deliverOrder')->setCssClass('btn btn-link')->displayIf(fn ($entity) => $entity->checkStatus(Purchase::STATUS_PAID));

        return $actions
            ->add(Crud::PAGE_INDEX, $acceptOrder)
            ->add(Crud::PAGE_INDEX, $confirmPayment)
            ->add(Crud::PAGE_INDEX, $deliverOrder)
            ->add(Crud::PAGE_EDIT, $acceptOrder)
            ->add(Crud::PAGE_EDIT, $confirmPayment)
            ->add(Crud::PAGE_EDIT, $deliverOrder)
            ->add(Crud::PAGE_DETAIL, $acceptOrder)
            ->add(Crud::PAGE_DETAIL, $confirmPayment)
            ->add(Crud::PAGE_DETAIL, $deliverOrder)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_EDIT, Action::DELETE);
    }

    public function acceptOrder(AdminContext $context, PurchaseManager $manager, AdminUrlGenerator $adminUrlGenerator)
    {
        $purchase = $context->getEntity()->getInstance();
        $manager->acceptOrder($purchase);
        $this->addFlash('success', 'La commande a ??t?? confirm??e');

        return $this->redirect($adminUrlGenerator->setController(self::class)->setAction(Action::INDEX)->generateUrl());
    }

    public function confirmPayment(AdminContext $context, PurchaseManager $manager, AdminUrlGenerator $adminUrlGenerator)
    {
        $purchase = $context->getEntity()->getInstance();
        $manager->confirmPayment($purchase);
        $this->addFlash('success', 'Le payement a ??t?? confirm??');

        return $this->redirect($adminUrlGenerator->setController(self::class)->setAction(Action::INDEX)->generateUrl());
    }

    public function deliverOrder(AdminContext $context, PurchaseManager $manager, AdminUrlGenerator $adminUrlGenerator)
    {
        $purchase = $context->getEntity()->getInstance();
        $manager->deliverOrder($purchase);
        $this->addFlash('success', 'La livraison a ??t?? confirm??e');

        return $this->redirect($adminUrlGenerator->setController(self::class)->setAction(Action::INDEX)->generateUrl());
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('status')
                ->setChoices([Purchase::STATUS_CREATED => Purchase::STATUS_CREATED, Purchase::STATUS_ACCEPTED => Purchase::STATUS_ACCEPTED, Purchase::STATUS_PAID => Purchase::STATUS_PAID, Purchase::STATUS_DELIVERED => Purchase::STATUS_DELIVERED])
                ->setLabel('Statut'))
            ->add(DateTimeFilter::new('purchasedAt')->setLabel('Date de commande'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('purchasedAt', 'Date de commande'),
            AssociationField::new('user', 'Utilisateur')->setFormattedValue('firstName'),
            MoneyField::new('total', 'Montant')->setCurrency('EUR'),
            TextField::new('status', 'Statut'),
            TextField::new('paymentMethod', 'Mode de r??glment'),
            BooleanField::new('isPaid', 'Paiement fait'),
            AssociationField::new('purchaseItems', 'D??tail de la commande')->formatValue(function ($value, Purchase $entity) {
                $str = $entity->getPurchaseItems()[0];
                $count = $entity->getPurchaseItems()->count();
                for ($i = 1; $i < $count; ++$i) {
                    $str = $str . '</br> ' . $entity->getPurchaseItems()[$i];
                }

                return $str;
            }),
        ];
    }
}

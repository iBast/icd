<?php

namespace App\Controller\Admin;

use App\Entity\Account;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class AccountCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Account::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Compte')
            ->setEntityLabelInPlural('Comptes');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            NumberField::new('number', 'Numéro de compte'),
            TextField::new('name', 'Nom du compte'),
            CollectionField::new('accountings', 'Détail du compte')->hideOnIndex(),
            MoneyField::new('debit', 'Débit')->setCurrency('EUR')->onlyOnIndex(),
            MoneyField::new('credit', 'Crédit')->setCurrency('EUR')->onlyOnIndex()
        ];
    }
}

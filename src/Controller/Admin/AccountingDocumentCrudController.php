<?php

namespace App\Controller\Admin;

use App\Entity\AccountingDocument;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AccountingDocumentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AccountingDocument::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('path', 'Chemin d\'accès'),
            ChoiceField::new('type', 'Type')->setChoices(['Facture d\'achat' => 0, 'Facture de vente' => 1]),
            MoneyField::new('totalAmount', 'Montant total')->setCurrency('EUR'),
        ];
    }
}

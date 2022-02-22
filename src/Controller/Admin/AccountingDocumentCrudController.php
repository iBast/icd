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

use App\Entity\AccountingDocument;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class AccountingDocumentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AccountingDocument::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Pièce')
            ->setEntityLabelInPlural('Pièces');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('path', 'Pièce')
                ->onlyOnDetail()
                ->setBasePath($this->getParameter('accounting_docs')),
            TextareaField::new('accountingFile', 'Pièce')
                ->onlyOnForms()
                ->setFormType(\Vich\UploaderBundle\Form\Type\VichImageType::class),
            ChoiceField::new('type', 'Type')->setChoices(['Facture d\'achat' => 0, 'Facture de vente' => 1]),
            MoneyField::new('totalAmount', 'Montant total')->setCurrency('EUR'),
        ];
    }
}

<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Account;
use App\Entity\Accounting;
use App\Entity\AccountingDocument;
use App\Repository\AccountRepository;
use App\Repository\AccountingDocumentRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

class AccountingCrudController extends AbstractCrudController
{
    protected $accountingDocumentRepository;
    protected $accountRepository;

    public function __construct(AccountingDocumentRepository $accountingDocumentRepository, AccountRepository $accountRepository)
    {
        $this->accountingDocumentRepository = $accountingDocumentRepository;
        $this->accountRepository = $accountRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Accounting::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('account', 'Compte'))
            ->add(DateTimeFilter::new('date'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Entrée')
            ->setEntityLabelInPlural('Entrées');
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            DateField::new('date', 'Date'),
            AssociationField::new('accountingDocuments', 'Pièce'),
            AssociationField::new('account', 'Compte'),
            TextField::new('wording', 'Libélé'),
            MoneyField::new('debit', 'Débit')->setCurrency('EUR'),
            MoneyField::new('credit', 'Crédit')->setCurrency('EUR')

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::DELETE);
    }
}

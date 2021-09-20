<?php

namespace App\Controller\Admin;

use App\Entity\Member;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MemberCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Member::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Membre')
            ->setEntityLabelInPlural('Membres');
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            EmailField::new('email', 'Email'),
            TextField::new('mobile', 'Portable'),
            TextField::new('phone', 'Téléphone'),
            TextField::new('adress', 'Adresse')->hideOnIndex(),
            TextField::new('postCode', 'CP')->hideOnIndex(),
            TextField::new('city', 'Ville'),
            DateField::new('birthday', 'Date d\'anniversaire')

        ];
    }
}

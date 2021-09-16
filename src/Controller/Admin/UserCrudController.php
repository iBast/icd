<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            TextField::new('email', 'Email'),
            ChoiceField::new('roles', 'Role')->setChoices(User::ROLES)->allowMultipleChoices(),
            BooleanField::new('isVerified', 'Email vérifié')
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Season;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class SeasonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Season::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('year', 'Année'),
            BooleanField::new('current', 'Saison accutuelle'),
            BooleanField::new('enrollmentStatus', 'Ouverture des adhésions'),
            MoneyField::new('membershipCost', 'Coût de l\'adhésion')->setCurrency('EUR')->hideOnIndex(),
            MoneyField::new('swimCost', 'Coût du forfait piscine')->setCurrency('EUR')->hideOnIndex(),
            CollectionField::new('enrollments', 'Adhésions')->hideOnIndex()
        ];
    }
}

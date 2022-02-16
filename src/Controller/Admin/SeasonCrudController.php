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

use App\Entity\Season;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SeasonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Season::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Saison')
            ->setEntityLabelInPlural('Saisons');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('year', 'Année'),
            BooleanField::new('current', 'Saison accutuelle'),
            BooleanField::new('enrollmentStatus', 'Ouverture des adhésions'),
            MoneyField::new('membershipCost', 'Coût de l\'adhésion')->setCurrency('EUR')->hideOnIndex(),
            MoneyField::new('youngCost', 'Coût de l\'adhésion jeune')->setCurrency('EUR')->hideOnIndex(),
            MoneyField::new('swimCost', 'Coût du forfait piscine')->setCurrency('EUR')->hideOnIndex(),
        ];
    }
}

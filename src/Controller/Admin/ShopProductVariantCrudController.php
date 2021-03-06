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

use App\Entity\ShopProductVariant;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ShopProductVariantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShopProductVariant::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Variante')
            ->setEntityLabelInPlural('Variantes');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('product', 'Produit pricipal'),
            TextField::new('name', 'Nom de la variante'),
            NumberField::new('stock', 'Stock'),
        ];
    }
}

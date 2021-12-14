<?php

namespace App\Controller\Admin;

use App\Entity\ShopProduct;
use App\Entity\ShopProductVariant;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ShopProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShopProduct::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('picturePath', 'Image')
                ->hideOnForm()
                ->setBasePath($this->getParameter('product_docs')),
            TextareaField::new('pictureFile', 'Image')->setFormType(VichImageType::class)->onlyOnForms(),
            TextField::new('name', 'Nom'),
            MoneyField::new('price', 'Prix')->setCurrency('EUR'),
            AssociationField::new('category', 'Catégorie'),
            TextField::new('slug', 'Slug')->hideOnForm(),
            TextField::new('reference', 'ref')
        ];
    }
}

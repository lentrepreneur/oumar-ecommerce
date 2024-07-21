<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ImageFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un produit')
            ->setEntityLabelInPlural('Les produits')
            ->setPaginatorPageSize(15)
            ->setPageTitle('index', 'List de Produit')
            ->setPageTitle('edit', 'Modification de Produit')
            ->setPageTitle('detail', 'Detail de Produit')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom du produit'),
            MoneyField::new('price', 'Prix du produit')->setCurrency('XAF'),
            PercentField::new('discount', 'Reduction/Promo (en %)'),
            TextEditorField::new('shortDescription', 'Description Courte du Produit')->onlyOnForms(),
            TextEditorField::new('description', 'Description Complet du Produit')->onlyOnForms(),
            CollectionField::new('images')->setEntryType(ImageFormType::class)->onlyOnForms(),
        ];
    }
}

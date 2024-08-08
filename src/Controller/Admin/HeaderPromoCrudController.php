<?php

namespace App\Controller\Admin;

use App\Entity\HeaderPromo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class HeaderPromoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HeaderPromo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre'),
            TextField::new('description', 'Courte description'),
            UrlField::new('link', 'Le lien vers le produit'),
            ImageField::new('leftImage', 'L\'image d\'a gauche')
                ->setBasePath('images/upload')
                ->setUploadDir('public/images/upload')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
                ->setFormTypeOption('allow_delete', false),

            ImageField::new('rightImage', 'L\'image d\'a droite')
                ->setBasePath('images/upload')
                ->setUploadDir('public/images/upload')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
                ->setFormTypeOption('allow_delete', false),
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\SiteInformation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SiteInformationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SiteInformation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('siteName', 'Nom de l\'entreprise'),
            TextField::new('address', 'Adresse de l\'entreprise'),
            EmailField::new('email', 'Email de l\'entreprise'),
            TelephoneField::new('phone', 'Telephone de l\'entreprise'),

            ImageField::new('logo', 'Logo de l\'entreprise')
                ->setBasePath('images/upload')
                ->setUploadDir('public/images/upload')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
        ];
    }

}

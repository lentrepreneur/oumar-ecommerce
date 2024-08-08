<?php

namespace App\Controller\Admin;

use App\Entity\AboutInfo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class AboutInfoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AboutInfo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextEditorField::new('description'),
            ImageField::new('image', 'Image ulistrative')
                ->setBasePath('images/upload')
                ->setUploadDir('public/images/upload')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
                ->setFormTypeOption('allow_delete', true),
        ];

    }
}

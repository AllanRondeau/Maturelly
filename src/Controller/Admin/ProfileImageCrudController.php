<?php

namespace App\Controller\Admin;

use App\Entity\ProfileImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class ProfileImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProfileImage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('fileName', 'Image')
                ->setBasePath('uploads/profile_images')
                ->setUploadDir('public/uploads/profile_images')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Profile;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProfileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Profile::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('firstName', 'First Name'),
            TextField::new('familyName', 'Family Name'),
            ImageField::new('profilePicture', 'Profile Picture')
                ->setBasePath('uploads/profile_images')
                ->setUploadDir('public/uploads/profile_images')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('address'),
            TextField::new('city'),
            TextField::new('region'),
            TextField::new('country'),
            TextEditorField::new('description')->hideOnIndex(),
            DateField::new('birthday'),
            TextField::new('code', 'Code'),
            TextField::new('postalCode', 'Postal Code'),
            AssociationField::new('user')->setCrudController(UserCrudController::class),
            AssociationField::new('hobbies')->setCrudController(HobbyCrudController::class),
            AssociationField::new('images')->setCrudController(ProfileImageCrudController::class),
        ];
    }
}

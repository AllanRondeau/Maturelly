<?php

namespace App\Form;

use App\Entity\Profile;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\Genders;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstName', TextType::class, [
            'label' => 'Prénom',
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le prénom est requis.']),
                new Assert\Length([
                    'max' => 50, 
                    'maxMessage' => 'Le prénom ne peut pas dépasser 50 caractères.',
                ]),
            ],
        ])
        ->add('familyName', TextType::class, [
            'label' => 'Nom de famille',
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 50]),
            ],
        ])
        ->add('gender', ChoiceType::class, [
            'label' => 'Genre',
            'choices' => [
                'Homme' => Genders::MALE,
                'Femme' => Genders::FEMALE,
            ],
            'expanded' => false,
            'multiple' => false,
        ])
        ->add('profilePicture', FileType::class, [
            'label' => 'Photo de profil',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new Assert\File([
                    'maxSize' => '2M',
                    'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                    'mimeTypesMessage' => 'Veuillez télécharger une image valide (jpeg, png, gif).',
                    'maxSizeMessage' => 'La taille de l\'image ne doit pas dépasser 2 Mo.',
                ])
            ]
        ])
        ->add('address', TextType::class, [
            'label' => 'Adresse',
        ])
        ->add('city', TextType::class, [
            'label' => 'Ville',
        ])
        ->add('region', TextType::class, [
            'label' => 'Région',
        ])
        ->add('country', CountryType::class, [
            'label' => 'Pays',
            'placeholder' => 'Votre pays',
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Bio',
            'required' => false,
        ])
        ->add('age', IntegerType::class, [
            'label' => 'Âge',
            'constraints' => [
                new Assert\NotBlank(['message' => 'L\'âge est requis.']),
                new Assert\Range([
                    'min' => 18, 
                    'max' => 120, 
                    'notInRangeMessage' => 'Vous devez avoir au moins 18 ans pour vous inscrire. Saisissez un âge réaliste.',
                ]),
            ],
        ])
        ->add('birthday', DateType::class, [
            'label' => 'Date de naissance',
            'widget' => 'single_text',
            'html5' => true,
            'constraints' => [
                new Assert\NotBlank(['message' => 'La date de naissance est requise.']),
                new Assert\Type([
                    'type' => \DateTimeInterface::class, 
                    'message' => 'Veuillez entrer une date valide.',
                ]),
            ],
        ])
        ->add('hobbies', CollectionType::class, [
            'label' => 'Loisirs',
            'entry_type' => HobbyFormType::class,
            'entry_options' => [
                'label' => false,
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'required' => false,
        ])
        ->add('images', CollectionType::class, [
            'mapped' => false,
            'label' => 'Votre galerie d\'images',
            'entry_type' => FileType::class,
            'entry_options' => [
                'label' => 'Image',
            ],
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'required' => false,
            'attr' => [
                'class' => 'images-collection'
            ],
            'constraints' => [
                new Assert\Count([
                    'max' => 5,
                    'maxMessage' => 'Vous pouvez télécharger jusqu\'à 5 images uniquement.',
                ])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
        ->add('profilePicture', FileType::class, [
            'label' => 'Photo de profil',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new Assert\File([
                    'maxSize' => '2M',
                    'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                    'mimeTypesMessage' => 'Veuillez télécharger une image valide (jpeg, png, gif).',
                    'maxSizeMessage' => 'La taille de l\'image ne doit pas dépasser 2 Mo.',
                ])
            ]
        ])
        ->add('address', TextType::class, [
            'label' => 'Adresse',
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 50]),
            ],
        ])
        ->add('city', TextType::class, [
            'label' => 'Ville',
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 50]),
            ],
        ])
        ->add('region', TextType::class, [
            'label' => 'Région',
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 50]),
            ],
        ])
        ->add('country', CountryType::class, [
            'label' => 'Pays',
            'placeholder' => 'Votre pays',
            'preferred_choices' => ['FR'],
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 50]),
            ],
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Bio',
            'required' => false,
            'constraints' => [
                new Assert\Length(['max' => 2000]),
            ],
        ])
        ->add('birthday', DateType::class, [
            'label' => 'Date de naissance',
            'constraints' => [
                new Assert\NotBlank(['message' => 'La date de naissance est requise.']),
                new Assert\Type([
                    'type' => \DateTimeInterface::class, 
                    'message' => 'Veuillez entrer une date valide.',
                ]),
                new Assert\Callback(function ($object, ExecutionContextInterface $context) {
                    $currentDate = new \DateTime();
                    $age = $currentDate->diff($object)->y;
        
                    if ($age < 18) {
                        $context->buildViolation('Vous devez avoir au moins 18 ans pour vous inscrire.')
                            ->atPath('birthday')
                            ->addViolation();
                    } else if ($age > 120) {
                        $context->buildViolation('Veuillez renseigner une date de naissance réaliste.')
                            ->atPath('birthday')
                            ->addViolation();
                    }
                }),
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
            'constraints' => [
                new Assert\Count([
                    'max' => 10,
                    'maxMessage' => 'Vous pouvez ajouter jusqu\'à 10 loisirs uniquement.',
                ]),
            ]
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
                ]),
                new Assert\All([
                    'constraints' => [
                        new Assert\File([
                            'maxSize' => '2M',
                            'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                            'mimeTypesMessage' => 'Veuillez télécharger une image valide (jpeg, png, gif).',
                            'maxSizeMessage' => 'La taille de l\'image ne doit pas dépasser 2 Mo.',
                        ]),
                    ],
                ]),
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

<?php

namespace App\Form;

use App\Entity\Hobby;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class HobbyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => ' ',
                'attr' => [
                    'placeholder' => 'Un loisir...',
                    'class' => 'bg-transparent border border-white rounded-sm px-2 py-1 text-xl md:text-md'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le loisir ne peut pas être vide.'
                    ]),
                    new Assert\Length([
                        'max' => 50,
                        'maxMessage' => 'Le loisir ne peut pas dépasser 50 caractères.'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hobby::class,
        ]);
    }
}

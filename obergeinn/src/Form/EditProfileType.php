<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('lastname', TextType::class, [
            'label' => 'Nom',
            'row_attr' => [
                'class' => 'edit-profile-label'
            ]
        ])
        ->add('firstname', TextType::class, [
            'label' => 'Prénom',
            'row_attr' => [
                'class' => 'edit-profile-label'
            ]
        ])
        ->add('pseudo', TextType::class, [
            'row_attr' => [
                'class' => 'edit-profile-label'
            ]
        ])
        ->add('email', EmailType::class, [
            'row_attr' => [
                'class' => 'edit-profile-label'
            ]
        ])            // Add a new field to the form in order to upload an image which is in the User Entity
        ->add('image', FileType::class, [
            'label' => 'Choisir un avatar (png ou jpeg)',

            // unmapped means that this field is not associated to any entity property
            'mapped' => false,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/png',
                        'image/jpeg'
                    ],
                    'mimeTypesMessage' => 'Merci de ne choisir que des fichiers .png ou .jpeg',
                ])
            ],
            'row_attr' => [
                'class' => 'edit-profile-label'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

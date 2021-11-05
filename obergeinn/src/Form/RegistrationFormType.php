<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom'
                ],
                'row_attr' => [
                    'class' => 'registration-label'
                ],
                'required' => 'required',
                'trim' => true,
                 'constraints' => new NotBlank([
                    'message' => 'Merci de saisir votre nom',
                ]),
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom'
                ],
                'row_attr' => [
                    'class' => 'registration-label'
                ],
                'required' => 'required',
                'trim' => true,
                'constraints' => new NotBlank([
                    'message' => 'Merci de saisir votre prénom',
                ]),
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'placeholder' => 'Votre pseudo'
                ],
                'row_attr' => [
                    'class' => 'registration-label'
                ],
                'required' => 'required',
                'trim' => true,
                'constraints' => new NotBlank([
                    'message' => 'Merci de saisir un pseudo',
                ]),
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Votre email'
                ],
                'row_attr' => [
                    'class' => 'registration-label'
                ],
                'required' => 'required',
                'trim' => true,
                'constraints' => new NotBlank([
                    'message' => 'Merci de saisir une adresse mail valide',
                ]),
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions',
                'row_attr' => [
                    'class' => 'registration-label'
                ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Votre mot de passe'
                ],
                'row_attr' => [
                    'class' => 'registration-label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            // Add a new field to the form in order to upload an image which is in the User Entity
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
                    'class' => 'registration-label registration-image',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

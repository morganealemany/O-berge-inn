<?php

namespace App\Form;

use App\Entity\Need;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class NeedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom',
                'trim' => true,
                'attr' => [
                    'placeholder' => 'Nom du besoin'
                ],
                'row_attr' => [
                    'class' => 'need-create-form-label'
                ],
                'required' => 'required',
                'constraints' => new NotBlank([
                    'message' => 'Merci de saisir le nom du besoin',
                ]),
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité',
                'trim' => true,
                'attr' => [
                    'placeholder' => 'Quantité nécessaire'
                ],
                'row_attr' => [
                    'class' => 'need-create-form-label'
                ],
                'required' => 'required',
                'constraints' => new NotBlank([
                    'message' => 'Merci de saisir la quantité',
                ]),
            ])
            ->add('measureUnit', null, [
                'label' => 'Unité de mesure',
                'trim' => true,
                'row_attr' => [
                    'class' => 'need-create-form-label'
                ],
            ])
            ->add('type', null, [
                'label' => 'Type de besoin',
                'trim' => true,
                'row_attr' => [
                    'class' => 'need-create-form-label'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Need::class,
        ]);
    }
}

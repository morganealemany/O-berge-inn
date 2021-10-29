<?php

namespace App\Form;

use App\Entity\Need;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NeedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom du besoin',
                'row_attr' => [
                    'class' => 'need-create-form-label'
                ],
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité',
                'row_attr' => [
                    'class' => 'need-create-form-label'
                ],
            ] )
            ->add('measureUnit', null, [
                'label' => 'Unité de mesure',
                'row_attr' => [
                    'class' => 'need-create-form-label'
                ],
            ])
            ->add('type', null, [
                'label' => 'Type de besoin',
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

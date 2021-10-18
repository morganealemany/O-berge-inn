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
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité'
            ] )
            ->add('measureUnit', null, [
                'label' => 'Unité de mesure'
            ])
            ->add('type', null, [
                'label' => 'Type de besoin'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Need::class,
        ]);
    }
}

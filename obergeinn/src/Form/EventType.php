<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', null, [
            'label' => 'Titre',
            'row_attr' => [
                'class' => 'event-edit-form-label'
            ],
        ])
        ->add('adress', null, [
            'label' => 'Adresse',
            'row_attr' => [
                'class' => 'event-edit-form-label'
            ],
        ])
        ->add('description', null, [
            'label' => 'Description',
            'row_attr' => [
                'class' => 'event-edit-form-label'
            ],
        ])
        ->add('date', DateTimeType::class, [
            'label' => 'Date',
            'widget'=> 'single_text',
            'input' => 'datetime_immutable',
            'row_attr' => [
                'class' => 'event-edit-form-label'
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

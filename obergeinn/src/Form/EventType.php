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
            'label' => 'Titre'
            // In the parameter of the add method (the property, the type of poperty, an array)
            // It allows us to modify the label of the input in the form
            // Labels are by défautl in English in the Event entity
        ])
        ->add('adress', null, [
            'label' => 'Adresse'
        ])
        ->add('description', null, [
            'label' => 'Description'
        ])
        ->add('date', DateTimeType::class, [
            'label' => 'Date',
            'widget'=> 'single_text',
            'input' => 'datetime_immutable',
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

<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', null, [
            'label' => 'Titre',
            'trim' => true,
            'row_attr' => [
                'class' => 'event-edit-form-label'
            ],
            'required' => 'required',
            'constraints' => new NotBlank([
                    'message' => 'Merci de saisir un titre pour l\'événement',
            ]),
        ])
        ->add('adress', null, [
            'label' => 'Adresse',
            'trim' => true,
            'row_attr' => [
                'class' => 'event-edit-form-label'
            ],
            'required' => 'required',
            'constraints' => new NotBlank([
                'message' => 'Merci de saisir une adresse',
            ]),
            'help' => 'Format : adresse - code postal - ville',
        ])
        ->add('description', null, [
            'label' => 'Description',
            'trim' => true,
            'row_attr' => [
                'class' => 'event-edit-form-label'
            ],
            'required' => 'required',
            'constraints' => new NotBlank([
                'message' => 'Merci de saisir une description',
            ]),
        ])
        ->add('date', DateTimeType::class, [
            'label' => 'Date',
            'widget'=> 'single_text',
            'input' => 'datetime_immutable',
            'trim' => true,
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

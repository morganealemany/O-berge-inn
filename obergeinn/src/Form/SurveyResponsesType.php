<?php

namespace App\Form;

use App\Entity\SurveyResponses;
use Doctrine\DBAL\Types\DateType as TypesDateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SurveyResponsesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('response', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('response2', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text'

            ])
            ->add('response3', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text'

            ])
            ->add('save', SubmitType::class)
            // ->add('nb_responses')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SurveyResponses::class,
        ]);
    }
}

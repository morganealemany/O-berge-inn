<?php
https://cam.oclock.io/?sessionId=1_MX40NjE5NjgyMn5-MTYzMzA4MTYxOTU1NX4rV2xJY0l5dXNRMUpXeTB4cDMzMmxzMkx-UH4&type=subscriber&object=screen&userName=bgultime
namespace App\Form;

use App\Entity\Need;
use App\Entity\Event;
use App\Entity\MeasureUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'trim' => true,
                'attr' => [
                    'placeholder' => 'Titre de l\'événement'
                ],
                'row_attr' => [
                    'class' => 'event-create-form-label'
                ],
                'required' => 'required',
                'constraints' => new NotBlank([
                    'message' => 'Merci de saisir un titre pour l\'événement',
                ]),
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
                'trim' => true,
                'attr' => [
                    'placeholder' => 'Adresse de l\'événement'
                ],
                'row_attr' => [
                    'class' => 'event-create-form-label'
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
                'attr' => [
                    'placeholder' => 'Description de l\'événement'
                ],
                'row_attr' => [
                    'class' => 'event-create-form-label'
                ],
                'required' => 'required',
                'constraints' => new NotBlank([
                    'message' => 'Merci de saisir une description',
                ]),
            ])
            ->add('date', DateTimeType::class, [
                'required' => false,
                'label' => 'Date',
                'widget'=> 'single_text',
                'input' => 'datetime_immutable',
                'trim' => true,
                'row_attr' => [
                    'class' => 'event-create-form-label'
                ],
            ])

            ->add('need', CollectionType::class, [
                'label' => 'Besoin(s)',
                'entry_type' => NeedType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'row_attr' => [
                    'class' => 'event-create-form-label'
                ],

            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
<?php
https://cam.oclock.io/?sessionId=1_MX40NjE5NjgyMn5-MTYzMzA4MTYxOTU1NX4rV2xJY0l5dXNRMUpXeTB4cDMzMmxzMkx-UH4&type=subscriber&object=screen&userName=bgultime
namespace App\Form;

use App\Entity\Need;
use App\Entity\Event;
use App\Entity\MeasureUnit;
use Doctrine\DBAL\Types\TextType as TypesTextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventCreateType extends AbstractType
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

            ->add('need', CollectionType::class, [
                'entry_type' => NeedType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

// ->add('imgUpload', FileType::class, [
//     'label' => 'Choisir une image',

//     // unmapped means that this field is not associated to any entity property
//     'mapped' => false,

//     // make it optional so you don't have to re-upload the PDF file
//     // every time you edit the Product details
//     'required' => false,

//     // unmapped fields can't define their validation using annotations
//     // in the associated entity, so you can use the PHP constraint classes
//     'constraints' => [
//         new File([
//             'maxSize' => '1024k',
//             'mimeTypes' => [
//                 'image/png',
//                 'image/jpeg'
//             ],
//             'mimeTypesMessage' => 'Merci de ne choisir que des fichiers .png et .jpeg',
//         ])
//     ],
// ])
// ;

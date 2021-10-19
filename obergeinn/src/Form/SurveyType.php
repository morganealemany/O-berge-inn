<?php

namespace App\Form;

use App\Entity\Survey;
use App\Repository\EventRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class SurveyType extends AbstractType
{

    // Use the construct function to access the user into the form
    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', HiddenType::class)
            ->add('event', null, [
                'label' => 'Pour quel événement?',
                // Filter the events to show only where the user connected is the organizer
                'query_builder' => function(EventRepository $eventRepository) {
                    $qb = $eventRepository->createQueryBuilder('e');
                    return $qb
                            ->where('e.user = :user')
                            ->setParameters([
                                'user' => $this->security->getUser(),
                            ]);
                },
            ])
            ->add('response1', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'label' => 'Premier choix'
            ])
            ->add('response2', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'label' => 'Deuxième choix'

            ])
            ->add('response3', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
                'label' => 'Troisième choix'

            ])       
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
            'userConnected' => 'Utilisateur connecté'
            ]);
    }
}

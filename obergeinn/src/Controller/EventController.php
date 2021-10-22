<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventCreateType;
use App\Entity\Assignation;
use App\Entity\Need;
use App\Repository\EventRepository;
use App\Repository\MeasureUnitRepository;
use App\Repository\NeedRepository;
use App\Repository\TypeRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/evenement", name="event_")
 * @IsGranted("ROLE_USER")
 */
class EventController extends AbstractController
{
    /**
     * Method allowing the display of the list of an event for a determined user
     * 
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function index(EventRepository $eventRepository): Response
    {
        // Manage the archiving of all the events according to the current date and the event date 
        $eventList = $eventRepository->findAll();

        foreach ($eventList as $event) {

            // Recover current date and event date
            $todayDate = new DateTimeImmutable();
            $eventDate = $event->getDate();

            // To compare it.
            if (isset($eventDate) && $eventDate < $todayDate) {
                if ($event->getStatus() == 0) {
                    //event already archived
                } else {
                    $em = $this->getDoctrine()->getManager();
                    $event->setStatus(0);
                    $em->flush();
                }
            }
        }
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    /**
     * Method allowing the display of the details of an event
     * 
     * @Route("/details/{id}", name="show", requirements={"id": "\d+"})
     *
     * @param int $id
     * @return void
     */
    public function show(int $id, EventRepository $eventRepository, NeedRepository $needRepository) : Response
    {
        $event = $eventRepository->find($id);

        $userConnected = $this->getUser()->getId();
        $eventOrganizer = $event->getUser()->getId();
        
        $guestList = [];

        foreach ($event->getParticipation() as $value) {
       
            $eventGuest = $value->getUser()->getId();
            array_push($guestList, $eventGuest);
        };

        // If the user who try to access the current page is different to the event organizer or to an event guest, a 403 exception will be thrown
        if ($userConnected != $eventOrganizer) {
            if (in_array($userConnected, $guestList) === false) {
                throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour accèder à cette page');
            }
        };

        $formArray = [];
        
        // If the modal input is not null 
        if ($_POST) {
            foreach ($event->getNeed() as $singleNeed) {

                $formArray[$singleNeed->getId()] = filter_input(INPUT_POST, 'quantity-assignees'.$singleNeed->getId(), FILTER_VALIDATE_INT);
            };
            foreach ($formArray as $needId => $quantity) {
                if ($quantity > 0) {
                    $assignation = new Assignation();
                    $assignation->setQuantity($quantity);
                    $assignation->setUser($this->getUser());
                    $assignation->setNeed($needRepository->find($needId));
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($assignation);
                }
            }
            $entityManager->flush();

            $this->addFlash('success', 'Vos assignations de besoins ont bien été enregistrées');
        }

        // Calculate percent for progress bar of needs
        $totalNeedsQuantity = 0;
        $totalAssignatedNeedsQuantity = 0;
        foreach ($event->getNeeds() as $need) {
            $totalNeedsQuantity += $need->getQuantity();
            foreach ($need->getAssignations() as $assignation) {
                $totalAssignatedNeedsQuantity += $assignation->getQuantity();
            }
        };
        if ($totalNeedsQuantity <= 0) {
            $needsPercent = 0;
        } else {
            $needsPercent = number_format(($totalAssignatedNeedsQuantity*100)/$totalNeedsQuantity,0);
        }



        if (!$event) {
            throw $this->createNotFoundException()("L'événement n'existe pas");
        }
        return $this->render('event/show.html.twig', [
            'event' => $event,
            'needsPercent' => $needsPercent,
        ]);
    }

    /**
     * Method allowing the display of the creation page of an event
     * 
     * @Route("/creer", name="create")
     *
     * @return void
     */
    public function create(Request $request, TypeRepository $typeRepository, MeasureUnitRepository $measureUnitRepository) 
    {
        // 1st step : we will "instance" an empty object
        $event = new Event();

        // 2nd step : we will "instant" the formtype of our event.
        // we link the instance $event with our form
        $form = $this->createForm(EventCreateType::class, $event);
        
        // 4th step : we receive the data submitted by the form
        // which are injected in the object $event
        $form->handleRequest($request);
        $event->setUser($this->getUser());
        $event->setStatus(1);
        // 5th stage : we check if we are in the case of submission of form before saving
        if ($form->isSubmitted()) {
            
            // we will save the datas and create the new event.
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            // for each informed need into the form, we will save it
            foreach ($form->getData()->getNeed() as $need) {
                $em->persist($need);
            }

            $em->flush();

            // Add a flash message to inform the user of the successing creation
            $this->addFlash('success', 'L\'événement ' . $event->getTitle() . ' a bien été créé. Vous pouvez maintenant prévenir vos invités.' );
            // after the form is submitted We will redirect in the detail of the new event created?
            return $this->redirectToRoute('mailer');

        }

        // 3rd step : templates/event/create.html.twig
        return $this->render('event/create.html.twig', [
            'formView' => $form->createView()
        ]);
    }
     /**
     * 
     * Method allowing user to accept an event invitation
     * 
     * @Route("/details/{id}/accept-participate", name="accept", requirements={"id": "\d+"})
     *
     * @return void
     */
    public function eventParticipationAccept(int $id, EventRepository $eventRepository) 
    {
        $event = $eventRepository->find($id);

        $userConnectedParticipation = $this->getUser()->getParticipation();

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($userConnectedParticipation as $userParticipation) {

            if ($event->getId() === $userParticipation->getEvent()->getId()) {
                // dump($userParticipation->getStatus());
                if ($userParticipation->getStatus() == false) {
                    $userParticipation->setStatus(true);
                    $entityManager->flush();
                }
            }
        }
        return $this->redirectToRoute('event_show', [
            'id' => $id,
        ]);
    }

    /**
     * 
     * Method allowing user to deny an event invitation
     * 
     * @Route("/details/{id}/refuser", name="deny", requirements={"id": "\d+"})
     *
     * @return void
     */
    public function eventParticipationDeny(int $id, EventRepository $eventRepository) 
    {
        $event = $eventRepository->find($id);
        $em = $this->getDoctrine()->getManager();

        $userConnectedParticipation = $this->getUser()->getParticipation();

        // We remove the user assignations for the current event
        foreach ($this->getUser()->getAssignations() as $assignation) {
            if ($assignation->getNeed()->getEvent() == $event) {
                $em->remove($assignation);
                $em->flush();
            }
        }
        // We remove the user participation for the current event
        foreach ($userConnectedParticipation as $userParticipation) {

            if ($event->getId() === $userParticipation->getEvent()->getId()) {
                $em->remove($userParticipation);
                $em->flush();

                $this->addFlash('warning', 'Vous ne participez plus à l\'événement ' . $event->getTitle() .'.');
            }
        }

        return $this->redirectToRoute('dashboard', [
            'id' => $id,
        ]);
    }

    /**
     * Method dealing the arhiving of an event
     * 
     * @Route("/{id}/archiver", name="archiving")
     *
     * @param integer $id
     * @return Response
     */
    public function archiving(int $id, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($id);

        $em = $this->getDoctrine()->getManager();
        if ($event->getStatus() == 1) {
            $event->setStatus(0);
            $this->addFlash('warning', 'L\'événement ' . $event->getTitle() . ' a bien été archivé');
        } else {
            $event->setStatus(1);
            $this->addFlash('warning', 'L\'événement ' . $event->getTitle() . ' a bien été sorti des archives');
        }
        $em->flush();

        return $this->redirectToRoute('event_index');
    }
}

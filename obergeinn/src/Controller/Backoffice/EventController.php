<?php

namespace App\Controller\Backoffice;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
* @Route("/backoffice/evenement", name="backoffice_event_", requirements={"id": "\d+"})
* @IsGranted("ROLE_ADMIN")
*/
class EventController extends AbstractController
{
    /**
     * 
     * URL: /backoffice/evenement/
     * Route : backoffice_event_index
     * 
     * @Route("/", name="index")
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('backoffice/event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    /**
     * Page which enable to see the detailed information of an existing event
     * URL : /backoffice/evenement/editer/{id}
     * Route : backoffice_event_edit
     * 
     * @Route("/editer/{id}", name="edit")
     *
     * @return Response
     */
    // Use of param converter in order to display an error 404 if the user id doesn't exist
    public function edit(Event $event, Request $request)
    {
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        // We check that the datas are valid before saving them.
        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backoffice_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/event/edit.html.twig', [
            'event' => $event,
            'eventView' => $form,
        ]);
    }

    /**
     * Page which allow the removal of an account.
     * 
     * URL : /backoffice/evenement/supprimer/{id}
     * Route : backoffice_event_delete
     * 
     * @Route("/supprimer/{id}", name="delete")
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backoffice_event_index', [], Response::HTTP_SEE_OTHER);
    }
}

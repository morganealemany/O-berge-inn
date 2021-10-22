<?php

namespace App\Controller;

use App\Repository\EventRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DashboardController extends AbstractController
{
    /**
     * @Route("/tableau-de-bord", name="dashboard")
     * @IsGranted("ROLE_USER")
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
        return $this->render('dashboard/index.html.twig');
    }
}

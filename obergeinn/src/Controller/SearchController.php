<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/search", name="search_")
 * @IsGranted("ROLE_USER")
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, EventRepository $eventRepository): Response
    {
        $userId = $this->getUser()->getId();
        // We retrieve the keyword entered in the search form
        $query = $request->query->get('search');

        // We retrieve all datas which contains the keyword
        $resultsOrganized = $eventRepository->searchEventsOrganizedByTitle($query, $userId);
        $resultsInvited = $eventRepository->searchEventsInvitedByTitle($query, $userId);

        $results = array_merge($resultsOrganized, $resultsInvited);
        
        if (empty($results)) {
            $this->addFlash('warning', 'Aucuns résultats pour votre recherche : "' . $query . '"');
        }
        return $this->render('search/index.html.twig', [
            'results' => $results,
        ]);
    }
}

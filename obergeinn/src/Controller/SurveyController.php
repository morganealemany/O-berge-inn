<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Entity\SurveyChoice;
use App\Entity\SurveyResponses;
use App\Form\SurveyType;
use App\Repository\EventRepository;
use App\Repository\SurveyRepository;
use App\Repository\SurveyResponsesRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/sondage", name="survey_", requirements={"id": "\d+"})
 */
class SurveyController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @IsGranted("ROLE_USER")
     */
    public function index(SurveyRepository $surveyRepository): Response
    {
        $surveyList = $surveyRepository->findAll();

        $currentSurveyList = [];
        // For each survey 
        foreach ($surveyList as $survey) {
            // If the survey.event organizer and the user connected are the same, fill the array with the $survey
            if ($survey->getEvent()->getUser() == $this->getUser()) {
                array_push($currentSurveyList, $survey);
            };
        }
        dump($currentSurveyList);
        return $this->render('survey/index.html.twig', [
            'surveyList' => $currentSurveyList,
        ]);
    }

    /**
     * @Route("/creer", name="create")
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $survey = new Survey();
        $user =$this->getUser();
        
        $form = $this->createForm(SurveyType::class, $survey, array('userConnected' => $user));
        
        $form->handleRequest($request);
        
        if($form->isSubmitted()) {
            // Survey creation
            $survey->setStatus(1);
            $em= $this->getDoctrine()->getManager();
            $em->persist($survey);

            // SurveyResponses creation (limit 3)
            // Transform the string given by the input into a DateTime instance
            $firstDate = DateTime::createFromFormat('Y-m-d',$_POST['survey']['response1']);
            $surveyResponses1 = new SurveyResponses();
            $surveyResponses1->setResponse($firstDate);
            $surveyResponses1->setSurvey($survey);
            $em->persist($surveyResponses1);
            dump($surveyResponses1);
    
            $secondDate = DateTime::createFromFormat('Y-m-d',$_POST['survey']['response2']);
            $surveyResponses2 = new SurveyResponses();
            $surveyResponses2->setResponse($secondDate);
            $surveyResponses2->setSurvey($survey);
            $em->persist($surveyResponses2);
            dump($surveyResponses2);

            $thirdDate = DateTime::createFromFormat('Y-m-d',$_POST['survey']['response3']);
            $surveyResponses3 = new SurveyResponses();
            $surveyResponses3->setResponse($thirdDate);
            $surveyResponses3->setSurvey($survey);
            $em->persist($surveyResponses3);
            dump($surveyResponses3);

            $em->flush();

            // Add a flash message to inform the user of the successing creation
            $this->addFlash('success', 'Le sondage pour l\'événement ' . $survey->getEvent() . ' a bien été créé.' );
            // after the form is submitted We will redirect in the detail of the new event created?
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('survey/create.html.twig', [
            'formView' => $form->createView()
        ]);
    }
    
    /**
     * Method to delete a survey from its id
     * 
     * @Route("/{id}/supprimer", name="delete")
     *
     * @param integer $id
     * @return Response
     */
    public function delete(int $id, SurveyRepository $surveyRepository): Response
    {
        $survey = $surveyRepository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($survey);
        $em->flush();

        $this->addFlash('warning', 'Le sondage pour l\'événement ' . $survey->getEvent()->getTitle() . ' a bien été supprimé.');

        return $this->redirectToRoute('survey_index');
    }

    /**
     * Method allowing the activation or desactivation of the survey
     *
     * @Route("/{id}/activer", name="activate")
     * 
     * @param integer $id
     * @param SurveyRepository $surveyRepository
     * @return Response
     */
    public function activate(int $id, SurveyRepository $surveyRepository): Response
    {
        $survey = $surveyRepository->find($id);

        if ($survey->getStatus() == 1) {
            $em = $this->getDoctrine()->getManager();
            $survey->setStatus(0);
        }
        else {
            $em = $this->getDoctrine()->getManager();
            $survey->setStatus(1);
        }

        $em->flush();

        return $this->redirectToRoute('survey_index');
    }


    /**
     * Methods dealing the answered for the survey for an event by his id
     *
     * @Route("/{id}/reponse", name="answered")
     * 
     * @return Response
     */
    public function answered(int $id, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($id);

        // We have to retrieve the survey_responses data into an array to use it
        $responsesList = [];
        foreach ($event->getSurvey()->getSurveyResponses() as $responses) {
           array_push($responsesList, $responses);
        }

        // For each choice we are checking if it exists before saving it on the DB
        if (isset($_POST['dateChoice1'])) {
            $choice1= filter_input(INPUT_POST, 'dateChoice1', FILTER_DEFAULT);
            $surveyChoice1 = new SurveyChoice();
            $em = $this->getDoctrine()->getManager();
            $surveyChoice1->setUser($this->getUser());
            $surveyChoice1->setChoice(1);
            $surveyChoice1->setSurveyResponses($responsesList[0]);
            $em->persist($surveyChoice1);

            // We have to increment the nb_responses into the table survey_responses
            $surveyResponses1 = $responsesList[0];

            $totalResponses = $surveyResponses1->getNbResponses();

            $em = $this->getDoctrine()->getManager();
            $surveyResponses1->setNbResponses($totalResponses +1);
        }
        if (isset($_POST['dateChoice2'])) {
            $choice2= filter_input(INPUT_POST, 'dateChoice2', FILTER_DEFAULT);
            $surveyChoice2 = new SurveyChoice();
            $em = $this->getDoctrine()->getManager();
            $surveyChoice2->setUser($this->getUser());
            $surveyChoice2->setChoice(2);
            $surveyChoice2->setSurveyResponses($responsesList[1]);
            $em->persist($surveyChoice2);

            // We have to increment the nb_responses into the table survey_responses
            $surveyResponses2 = $responsesList[1];

            $totalResponses = $surveyResponses2->getNbResponses();

            $em = $this->getDoctrine()->getManager();
            $surveyResponses2->setNbResponses($totalResponses +1);
        }
        if (isset($_POST['dateChoice3'])) {
            $choice3= filter_input(INPUT_POST, 'dateChoice3', FILTER_DEFAULT);
            $surveyChoice3 = new SurveyChoice();
            $em = $this->getDoctrine()->getManager();
            $surveyChoice3->setUser($this->getUser());
            $surveyChoice3->setChoice(3);
            $surveyChoice3->setSurveyResponses($responsesList[2]);
            $em->persist($surveyChoice3);

            // We have to increment the nb_responses into the table survey_responses
            
            $surveyResponses3 = $responsesList[2];

            $totalResponses = $surveyResponses3->getNbResponses();

            $em = $this->getDoctrine()->getManager();

            $surveyResponses3->setNbResponses($totalResponses +1);
        }
        $em->flush();

        $this->addFlash('success', 'Vos choix pour le sondage ont bien été pris en compte');
        
        return $this->redirectToRoute('event_show', [
            'id' => $id
        ]);
    }
}


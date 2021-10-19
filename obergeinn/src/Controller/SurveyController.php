<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Entity\SurveyResponses;
use App\Form\SurveyResponsesType;
use App\Form\SurveyType;
use App\Repository\SurveyRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/sondage", name="survey_")
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
}

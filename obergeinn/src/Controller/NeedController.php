<?php

namespace App\Controller;

use App\Entity\Need;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class NeedController extends AbstractController
{
    /**
     * Method allowing the display of the creation page of an event
     * @IsGranted("ROLE_USER")
     *
     * @return void
     */
    public function create(Request $request) 
    {
        // 1st step : we will "instance" an empty object
        $need = new Need();
       
        // 2nd step : we will "instant" the formtype of our need.
        // we link the instance $need with our form
        $form = $this->createForm(NeedType::class, $need);
        

        // 4th step : we receive the data submitted by the form
        // which are injected in the object $need
        $form->handleRequest($request);
        // dd($this->getUser());
        
        

        // 5th stage : we check if we are in the case of submission of form before saving
        if ($form->isSubmitted()) {
            
            // we will save the datas and create the new need.
            $em = $this->getDoctrine()->getManager();
            $em->persist($need);
            $em->flush();
  
        }

        // 3rd step : templates/need/create.html.twig
        return $this->render('need/create.html.twig', [
            'needView' => $form->createView()
        ]);
    }
}

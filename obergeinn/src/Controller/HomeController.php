<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("qui-sommes-nous", name="presentation")
     *
     * @return Response
     */
    public function presentation() : Response
    {
        return $this->render('home/presentation.html.twig');
    }

        /**
     * @Route("mentions-legales", name="legal-mentions")
     *
     * @return Response
     */
    public function legalMentions() : Response
    {
        return $this->render('home/legalMentions.html.twig');
    }

}

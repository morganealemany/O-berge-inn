<?php

namespace App\Controller;

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
    public function index(): Response
    {
     
        return $this->render('dashboard/index.html.twig');
    }
}

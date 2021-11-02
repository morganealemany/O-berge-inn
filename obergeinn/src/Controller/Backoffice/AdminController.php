<?php

namespace App\Controller\Backoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class AdminController extends AbstractController
{
    /**
     * Display a page with all the users and events in the BDD
     * 
     * URL : /backoffice/admin
     * Route : backoffice_admin_index
     * 
     * @Route("/backoffice/admin", name="backoffice_admin_index")
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        return $this->render('backoffice/admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
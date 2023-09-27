<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RepresentantHController extends AbstractController
{
    #[Route('/representanth', name: 'app_representant_h')]
    public function index(): Response
    {
        return $this->render('pages/representant_h/index.html.twig', [
            'controller_name' => 'RepresentantHController',
        ]);
    }
}

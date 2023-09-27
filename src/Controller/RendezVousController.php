<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RendezVousController extends AbstractController
{
    #[Route('/rendezvous', name: 'app_rendez_vous')]
    public function index(): Response
    {
        return $this->render('pages/rendez_vous/index.html.twig', [
            'controller_name' => 'RendezVousController',
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreEmploiController extends AbstractController
{
    #[Route('/offreemploi', name: 'app_offre_emploi')]
    public function index(): Response
    {
        return $this->render('pages/offre_emploi/index.html.twig', [
            'controller_name' => 'OffreEmploiController',
        ]);
    }
}

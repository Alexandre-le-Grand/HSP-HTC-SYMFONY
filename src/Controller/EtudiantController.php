<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant', name: 'app_etudiant')]
    #[IsGranted('ROLE_ETUDIANT')]
    public function index(): Response
    {
        return $this->render('pages/etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }
}

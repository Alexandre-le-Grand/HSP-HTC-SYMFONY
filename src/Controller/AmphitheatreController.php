<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AmphitheatreController extends AbstractController
{
    #[Route('/amphitheatre', name: 'app_amphitheatre')]
    public function index(): Response
    {
        return $this->render('pages/amphitheatre/index.html.twig', [
            'controller_name' => 'AmphitheatreController',
        ]);
    }
}

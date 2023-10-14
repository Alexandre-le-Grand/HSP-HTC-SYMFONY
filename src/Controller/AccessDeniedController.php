<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccessDeniedController extends AbstractController
{
    #[Route('/access_denied', name: 'access_denied.index')]
    public function index(): Response
    {
        return $this->render('pages/access_denied/index.html.twig', [
            'controller_name' => 'AccessDeniedController',
        ]);
    }
}

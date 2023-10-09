<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/administrateur', name: 'admin.index')]
    public function index(): Response
    {
        return $this->render('pages/admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}

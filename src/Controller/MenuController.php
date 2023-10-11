<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'menu.index')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $role = 'ROLE_USER';

        if ($this->isGranted('ROLE_ADMIN')) {
            $role = 'ROLE_ADMIN';
        } elseif ($this->isGranted('ROLE_ETUDIANT')) {
            $role = 'ROLE_ETUDIANT';
        } elseif ($this->isGranted('ROLE_REPRESENTANT')) {
            $role = 'ROLE_REPRESENTANT';
        }

        return $this->render('pages/menu/' . strtolower(str_replace('ROLE_', '', $role)) . '.html.twig');
    }

}

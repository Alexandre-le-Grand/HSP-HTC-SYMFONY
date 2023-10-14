<?php

namespace App\Controller;

use App\Repository\OffreEmploiRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreEmploiController extends AbstractController
{
    #[Route('/offre_emploi', name: 'offre_emploi.index')]
    #[IsGranted('ROLE_USER')]
    public function index(
        OffreEmploiRepository $repository,
        PaginatorInterface $paginator,
        Request $request): Response
    {
        $offre = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page',1),
            10
        );
        return $this->render('pages/offre_emploi/index.html.twig', [
            'offres' => $offre
        ]);
    }
}

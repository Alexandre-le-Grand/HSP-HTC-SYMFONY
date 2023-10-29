<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Repository\PostulationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostulationController extends AbstractController
{
    #[Route('/postulations/{id}', name: 'postulations.index', methods: ['GET'])]
    public function index(
        OffreEmploi $offreemploi,
        PostulationRepository $repository,
        PaginatorInterface $paginator,
        Request $request): Response
    {
        // partie pour bloquer un utilisateur non autorisé
        $user = $this->getUser();
        $statut = $user->isStatut();

        if ($statut === false) {
            return $this->redirectToRoute('access_denied.index');
        }
        // --------------------------------------------------------------

        $postulations = $paginator->paginate(
            $repository->findBy(['ref_offre' => $offreemploi]),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/postulation/index.html.twig', [
            'postulations' => $postulations,
            'offreemploi' => $offreemploi,
        ]);
    }
}

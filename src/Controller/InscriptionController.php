<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\InscriptionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription/{id}', name: 'inscription.index', methods: ['GET'])]
    public function index(
        Conference $conference,
        InscriptionRepository $repository,
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

        $inscriptions = $paginator->paginate(
            $repository->findBy(['ref_conference' => $conference]),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/inscription/index.html.twig', [
            'inscriptions' => $inscriptions,
            'conference' => $conference,
        ]);
    }

}

<?php

namespace App\Controller;

use App\Entity\Amphitheatre;
use App\Entity\OffreEmploi;
use App\Form\AmphitheatreType;
use App\Form\OffreEmploiType;
use App\Repository\OffreEmploiRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    #[Route('/offre_emploi/nouveau', 'offreemploi.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_REPRESENTANT')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $offreEmploi = new OffreEmploi();
        $form = $this->createForm(OffreEmploiType::class, $offreEmploi);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($offreEmploi);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'offre d'emploi a été créée avec succès !"
            );

            return $this->redirectToRoute('offreemploi_index');
        }

        return $this->render('pages/offre_emploi/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

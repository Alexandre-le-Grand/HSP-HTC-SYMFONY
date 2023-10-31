<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\PostulationRepository;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RendezVousController extends AbstractController
{
    #[Route('/rendezvous/{postulationId}', name: 'rendez_vous.index')]
    public function index(RendezVousRepository $rendezVousRepository,Request $request,$postulationId): Response
    {
        $rendezvous = $rendezVousRepository->findBy(['postulation' => $postulationId]);

        $rendezVous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVous);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('offre_emploi.index');
        }

        return $this->render('pages/rendez_vous/index.html.twig', [
            'rendezvous' => $rendezvous,
        ]);
    }

    #[Route('/rendez_vous/nouveau/{postulationId}', name: 'rendez_vous.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_REPRESENTANT')]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
        PostulationRepository $postulationRepository,
        $postulationId) : Response
    {
        $postulation = $postulationRepository->find($postulationId);

        $rendezVous = new RendezVous();
        $rendezVous->setRefRepresentantH($this->getUser());
        $rendezVous->setPostulation($postulation);

        $postulation = $rendezVous->getPostulation();
        $etudiant = $postulation->getRefEtudiant();
        $rendezVous->setRefEtudiant($etudiant);

        $offreEmploi = $postulation->getRefOffre();
        $rendezVous->setRefOffre($offreEmploi);

        $form = $this->createForm(RendezVousType::class, $rendezVous);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $rendezVous = $form->getData();

            $manager->persist($rendezVous);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le rendez-vous à été créé avec succès !'
            );

            return $this->redirectToRoute('rendez_vous.index');
        }

        return $this->render('pages/rendez_vous/new.html.twig', [
            'form' => $form->createView(),
            'postulation' => $postulation,
        ]);
    }
}

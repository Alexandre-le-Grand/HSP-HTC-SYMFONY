<?php

namespace App\Controller;

use App\Entity\OffreEmploi;
use App\Entity\Postulation;
use App\Entity\Utilisateur;
use App\Form\OffreEmploiType;
use App\Repository\OffreEmploiRepository;
use App\Repository\PostulationRepository;
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
        // partie pour bloquer un utilisateur non autorisé
        $user = $this->getUser();
        $statut = $user->isStatut();

        if ($statut === false) {
            return $this->redirectToRoute('access_denied.index');
        }
        // --------------------------------------------------------------

        if ($this->isGranted('ROLE_REPRESENTANT')) {

            $offre = $paginator->paginate(
                $repository->findBy(['ref_representant_h' => $user]),
                $request->query->getInt('page', 1),
                10
            );
        } else {
            $offre = $paginator->paginate(
                $repository->findAll(),
                $request->query->getInt('page', 1),
                10
            );
        }

        return $this->render('pages/offre_emploi/index.html.twig', [
            'offres' => $offre
        ]);
    }
    #[Route('/offre_emploi/nouveau', 'offreemploi.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_REPRESENTANT')]
    public function new(
        Request $request,
        EntityManagerInterface $manager): Response
    {
        $offreEmploi = new OffreEmploi();
        $offreEmploi->setRefRepresentantH($this->getUser());

        $form = $this->createForm(OffreEmploiType::class, $offreEmploi);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $typeContrat = $form->get('type_contrat')->getData();
            $offreEmploi->setTypeContrat($typeContrat);

            $manager->persist($offreEmploi);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'offre d'emploi a été créée avec succès !"
            );

            return $this->redirectToRoute('offre_emploi.index');
        }

        return $this->render('pages/offre_emploi/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/offre_emploi/postulation/{id}', 'offre_emploi.postulation', methods: ['GET'])]
    public function postulation (
        OffreEmploiRepository $offreEmploiRepository,
        PostulationRepository $postulationRepository,
        EntityManagerInterface $manager,
        $id): Response
    {
        $offreEmploi = $offreEmploiRepository->find($id);
        $user = $this->getUser();

        $existingPostulation = $postulationRepository->findOneBy([
            'refEtudiant' => $user,
            'refOffre' => $offreEmploi
        ]);

        if ($existingPostulation) {
            $this->addFlash(
                'warning',
                'Vous avez déjà postulé à cette offre.'
            );
        } else {
            $postulation = new Postulation();
            $postulation->setRefEtudiant($user);
            $postulation->setRefOffre($offreEmploi);

            $manager->persist($postulation);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre postulation a bien été enregistrée.'
            );
        }

        return $this->redirectToRoute('offre_emploi.index');
    }

}

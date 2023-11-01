<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\Inscription;
use App\Form\ConferenceType;
use App\Repository\AmphitheatreRepository;
use App\Repository\ConferenceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{
    /**
     * This function display all conferences
     * @param ConferenceRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/conference', name: 'conference.index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(
        ConferenceRepository $repository,
        AmphitheatreRepository $amphitheatreRepository,
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

        $currentDate = new DateTime();
        $amphitheatres = $amphitheatreRepository->findAll();

        $conferences = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/conference/index.html.twig', [
            'conferences' => $conferences,
            'currentDate' => $currentDate,
            'amphitheatreRepository' => $amphitheatreRepository,
            'amphitheatres' => $amphitheatres,
        ]);
    }

    #[Route('/conference/nouveau', 'conference.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $manager) : Response
    {
        $conference = new Conference();
        $conference->setRefUtilisateur($this->getUser());
        $conference->setStatut(0);

        $form = $this->createForm(ConferenceType::class, $conference);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $conference = $form->getData();

            $manager->persist($conference);
            $manager->flush();

            $this->addFlash(
                'success',
                'La conférence à été créé avec succès !'
            );
            return $this->redirectToRoute('conference.index');
        }

        return $this->render('pages/conference/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/conference/validation/{conferenceId}', name: 'conference.validation', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function validerConference(
        int $conferenceId,
        int $amphitheatreId,
        ConferenceRepository $conferenceRepository,
        AmphitheatreRepository $amphitheatreRepository,
        EntityManagerInterface $manager
    ): Response {
        $conference = $conferenceRepository->find($conferenceId);
        $amphitheatre = $amphitheatreRepository->find($amphitheatreId);


        $conference->setRefAmphi($amphitheatre);
        $conference->setStatut(true);

        $manager->persist($conference);
        $manager->flush();

        $this->addFlash(
            'success',
            'La conférence a été validée avec succès et un amphithéâtre lui a été attribué !'
        );

        return $this->redirectToRoute('conference.index');
    }

    #[Route('/conference/invalidation/{id}', 'conference.invalidation', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function invaliderConference(Conference $conference, EntityManagerInterface $manager): Response
    {
        $conference->setStatut(false);
        $manager->persist($conference);
        $manager->flush();

        $this->addFlash(
            'success',
            'La conférence a été invalidée avec succès !'
        );

        return $this->redirectToRoute('conference.index');
    }

    #[Route("/conference/selection_amphi", name: "select_amphitheatre", methods: ['GET','POST'])]
    public function selectAmphitheatre(
        AmphitheatreRepository $repository,
        PaginatorInterface $paginator,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $amphitheatres = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/conference/select_amphitheatre.html.twig', [
            'amphitheatres' => $amphitheatres,
        ]);
    }

    #[Route('/conference/inscription/{id}', 'conference.inscription', methods: ['GET'])]
    public function inscription(Conference $conference, EntityManagerInterface $manager): Response
    {
        $etudiant = $this->getUser();

        $inscription = new Inscription();
        $inscription->setRefEtudiant($etudiant);
        $inscription->setRefConference($conference);

        $manager->persist($inscription);
        $manager->flush();

        $this->addFlash(
            'success',
            'Vous avez été inscrit avec succès'
        );

        return $this->redirectToRoute('conference.index');
    }

    #[Route('/conference/desinscription/{id}', 'conference.desinscription', methods: ['GET'])]
    public function desinscription(Conference $conference, EntityManagerInterface $manager): Response
    {
        $etudiant = $this->getUser();

        $inscription = $manager->getRepository(Inscription::class)->findOneBy([
            'ref_etudiant' => $etudiant,
            'ref_conference' => $conference,
        ]);

        if ($inscription) {
            $manager->remove($inscription);
            $manager->flush();

            $this->addFlash(
                'success',
                'Vous avez été désinscrit avec succès'
            );

        }

        return $this->redirectToRoute('conference.index');
    }


}

<?php

namespace App\Controller;

use App\Entity\Amphitheatre;
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
use Symfony\Component\Mailer\MailerInterface;

class ConferenceController extends AbstractController
{
    /**
     * This function display all conferences
     * @param ConferenceRepository $repository
     * @param AmphitheatreRepository $amphitheatreRepository
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
        Request $request) : Response
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
        $repository->findAll();

        $conferences = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        $totalValidConferences = $repository->countValidConferences();

        return $this->render('pages/conference/index.html.twig', [
            'conferences' => $conferences,
            'currentDate' => $currentDate,
            'amphitheatreRepository' => $amphitheatreRepository,
            'amphitheatres' => $amphitheatres,
            'totalValidConferences' => $totalValidConferences,
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

    #[Route('/conference/modification/{id}', name: 'conference.modification', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(
        Conference $conference,
        Request $request,
        EntityManagerInterface $manager): Response
    {
        if ($this->getUser() !== $conference->getRefUtilisateur()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour modifier cette conférence.');
        }

        $form = $this->createForm(ConferenceType::class, $conference, [
            'is_edit' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash('success', 'La conférence a été modifiée avec succès.');
            return $this->redirectToRoute('conference.index');
        }

        return $this->render('pages/conference/edit.html.twig', [
            'form' => $form->createView(),
            'conference' => $conference,
        ]);
    }

    #[Route('/conference/suppression/{id}', name: 'conference.suppression', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(
        Conference $conference,
        EntityManagerInterface $manager): Response
    {
        $manager->remove($conference);
        $manager->flush();

        $this->addFlash('success', 'La conférence a été supprimée avec succès.');

        return $this->redirectToRoute('conference.index');
    }

    #[Route("/conference/selection_amphi", name: "select_amphitheatre", methods: ['GET','POST'])]
    public function indexAmphitheatre(
        ConferenceRepository $repository,
        AmphitheatreRepository $amphitheatreRepository,
        PaginatorInterface $paginator,
        Request $request) : Response
    {
        $amphitheatres = $paginator->paginate(
            $amphitheatreRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        $conferences = $repository->findAll();

        return $this->render('pages/conference/select_amphitheatre.html.twig', [
            'amphitheatres' => $amphitheatres,
            'conferences' => $conferences,
        ]);
    }

    #[Route("/conference/lier_amphi", name: "lier_amphitheatre", methods: ['GET', 'POST'])]
    public function lierAmphitheatre(
        MailerInterface $mailer,
        Request $request,
        EntityManagerInterface $entityManager,
        ConferenceRepository $conferenceRepository
    ) {
        $amphitheatreId = $request->get('amphitheatreId');
        $conferenceId = $request->get('conferenceId');

        $conference = $entityManager->getRepository(Conference::class)->find($conferenceId);
        $amphitheatre = $entityManager->getRepository(Amphitheatre::class)->find($amphitheatreId);

        if (!$conference || !$amphitheatre) {
            throw $this->createNotFoundException('La conférence ou l\'amphithéâtre n\'existe pas.');
        }

        $conferenceStartDate = new \DateTime($conference->getDate());
        $conferenceDuration = $conference->getDuree();
        $conferenceEndDate = clone $conferenceStartDate;
        $conferenceEndDate->add(new \DateInterval('PT' . (int) $conferenceDuration . 'M'));

        // Vérifie si l'amphithéâtre est disponible
        if (!$amphitheatre->isDisponible()) {
            $this->addFlash('danger', 'L\'amphithéâtre n\'est pas disponible.');
            return $this->redirectToRoute('conference.index');
        }

        // Vérifie si l'amphithéâtre est disponible pour la plage horaire de la conférence
        if (!$conferenceRepository->isAmphitheatreAvailableForTimeRange($amphitheatre, $conferenceStartDate, $conferenceEndDate, $conferenceId)) {
            $this->addFlash('danger', 'L\'amphithéâtre n\'est pas disponible pour cette plage horaire.');
            return $this->redirectToRoute('conference.index');
        }

        // Ajoute la conférence à l'amphithéâtre
        $amphitheatre->setDisponible(false);

        $heureFinString = $conferenceEndDate->format('d-m-Y H:i:s');
        $amphitheatre->setHeureFin($heureFinString);

        $conference->setRefAmphi($amphitheatre);
        $conference->setStatut(true);

        $entityManager->persist($conference);
        $entityManager->flush();

        $this->addFlash('success', 'L\'amphithéâtre a été lié à la conférence validée avec succès.');

        return $this->redirectToRoute('conference.index');
    }



    #[Route('/conference/invalidation/{id}', 'conference.invalidation', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function invaliderConference(
        MailerInterface $mailer,
        Request $request,
        Conference $conference,
        EntityManagerInterface $manager) : Response
    {
        $amphitheatre = $conference->getRefAmphi();

        if ($amphitheatre) {
            $conference->setRefAmphi(null);
        }

        $conference->setStatut(false);

        $manager->persist($conference);
        $manager->flush();

        $this->addFlash(
            'success',
            'La conférence a été invalidée avec succès !'
        );

        // Envoi d'e-mail
        /*
        $utilisateur = $this->getUser();
        $receveur = $conference->getRefUtilisateur();

        $email = (new Email())
            ->from($utilisateur->getEmail())
            ->to($receveur->getEmail())
            ->subject('Conférence invalidée')
            ->text('Votre conférence ' . $conference->getNom() . ' a été invalidée.');

        $mailer->send($email);
        */

        return $this->redirectToRoute('conference.index');
    }

    #[Route('/conference/inscription/{id}', 'conference.inscription', methods: ['GET'])]
    public function inscription(
        Conference $conference,
        EntityManagerInterface $manager
    ) : Response {
        $etudiant = $this->getUser();

        $inscription = $manager->getRepository(Inscription::class)->findOneBy([
            'ref_etudiant' => $etudiant,
            'ref_conference' => $conference,
        ]);

        if ($inscription) {
            $this->addFlash(
                'danger',
                'Vous êtes déjà inscrit à cette conférence.'
            );
        } else {
            $inscription = new Inscription();
            $inscription->setRefEtudiant($etudiant);
            $inscription->setRefConference($conference);

            $manager->persist($inscription);
            $manager->flush();

            $this->addFlash(
                'success',
                'Vous avez été inscrit avec succès'
            );
        }

        return $this->redirectToRoute('conference.index');
    }

    #[Route('/conference/desinscription/{id}', 'conference.desinscription', methods: ['GET'])]
    public function desinscription(
        Conference $conference,
        EntityManagerInterface $manager) : Response
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

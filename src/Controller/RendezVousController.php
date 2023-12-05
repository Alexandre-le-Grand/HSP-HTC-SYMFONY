<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\PostulationRepository;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RendezVousController extends AbstractController
{

    #[Route('/rendez_vous', name: 'rendez_vous.index')]
    public function index(
        RendezVousRepository $repository,
        PaginatorInterface $paginator,
        Request $request,
        Security $security
    ): Response {
        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('security.login');
        }

        $criteria = ['ref_representantH' => $user];

        if (in_array('ROLE_ETUDIANT', $user->getRoles())) {
            $criteria = ['ref_etudiant' => $user];
        }

        $rendezvous = $paginator->paginate(
            $repository->findBy($criteria),
            $request->query->getInt('page', 1),
            10
        );

        return  $this->render('pages/rendez_vous/index.html.twig', [
            'rendezvous' => $rendezvous
        ]);
    }


    #[Route('/rendez_vous/nouveau/{postulationId}', name: 'rendez_vous.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_REPRESENTANT')]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
        PostulationRepository $postulationRepository,
        MailerInterface $mailer,
        $postulationId) : Response
    {
        $postulation = $postulationRepository->find($postulationId);

        if (!$postulation) {
            $this->addFlash(
                'warning',
                'Il n\'y à pas de postulation à cette offre'
            );

            return $this->redirectToRoute('postulations.index');
        }

        $rendezVous = new RendezVous();
        $rendezVous->setRefRepresentantH($this->getUser());
        $rendezVous->setPostulation($postulation);
        $rendezVous->setStatut(false);

        if ($postulation) {
            $etudiant = $postulation->getRefEtudiant();
            $rendezVous->setRefEtudiant($etudiant);

            $offreEmploi = $postulation->getRefOffre();
            $rendezVous->setRefOffre($offreEmploi);
        }

        $form = $this->createForm(RendezVousType::class, $rendezVous);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $rendezVous = $form->getData();

            $manager->persist($rendezVous);
            $manager->flush();

            // Envoi d'e-mail
            $utilisateur = $this->getUser();

            $receveur = $rendezVous->getRefEtudiant();
            $representant = $rendezVous->getRefRepresentantH();

            $email = (new Email())
                ->from($utilisateur->getEmail())
                ->to($receveur->getEmail())
                ->subject('Nouveau Rendez-vous')
                ->text('Un nouveau rendez-vous vous a été proposé par ' . $representant->getNom() . ' ' . $representant->getPrenom());

            $mailer->send($email);

            return $this->redirectToRoute('rendez_vous.index', ['id' => $offreEmploi->getId()]);
        }

        return $this->render('pages/rendez_vous/new.html.twig', [
            'form' => $form->createView(),
            'postulation' => $postulation,
        ]);
    }


    #[Route('/rendez_vous/confirmation/{id}', name: 'confirmation_rendez_vous', methods: ['GET'])]
    #[IsGranted('ROLE_ETUDIANT')]
    public function confirmRendezVous(
        RendezVous $rendezVous,
        EntityManagerInterface $manager,
        MailerInterface $mailer): Response
    {
        if ($rendezVous->getRefEtudiant() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas la permission de confirmer ce rendez-vous.');
        }

        $rendezVous->setStatut(true);

        $manager->persist($rendezVous);
        $manager->flush();

        $etudiant = $rendezVous->getRefEtudiant();
        $representant = $rendezVous->getRefRepresentantH();
        $email = (new Email())
            ->from($etudiant->getEmail())
            ->to($representant->getEmail())
            ->subject('Confirmation de rendez-vous')
            ->text('Le rendez-vous a été confirmé par l\'étudiant.');

        $mailer->send($email);

        $this->addFlash('success', 'Rendez-vous confirmé avec succès, un e-mail a été envoyé au représentant.');

        return $this->redirectToRoute('rendez_vous.index');
    }


    #[Route('/rendez_vous/refus/{id}', name: 'refus_rendez_vous', methods: ['GET'])]
    #[IsGranted('ROLE_ETUDIANT')]
    public function refusRendezVous(
        RendezVous $rendezVous,
        EntityManagerInterface $manager,
        MailerInterface $mailer): Response
    {
        if ($rendezVous->getRefEtudiant() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas la permission de refuser ce rendez-vous.');
        }

        $rendezVous->setStatut(false);

        $manager->persist($rendezVous);
        $manager->flush();

        $etudiant = $rendezVous->getRefEtudiant();
        $representant = $rendezVous->getRefRepresentantH();
        $email = (new Email())
            ->from($etudiant->getEmail())
            ->to($representant->getEmail())
            ->subject('Refus de rendez-vous')
            ->text('Le rendez-vous a été refusé par l\'étudiant.');

        $mailer->send($email);

        $this->addFlash('success', 'Rendez-vous refusé avec succès, un e-mail a été envoyé au représentant.');

        return $this->redirectToRoute('rendez_vous.index');
    }
}

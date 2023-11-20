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
use Symfony\Component\Mime\Email;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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
        $conferences = $repository->findAll();

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
        EntityManagerInterface $entityManager)
    {
        $amphitheatreId = $request->get('amphitheatreId');
        $conferenceId = $request->get('conferenceId');

        $conference = $entityManager->getRepository(Conference::class)->find($conferenceId);
        $amphitheatre = $entityManager->getRepository(Amphitheatre::class)->find($amphitheatreId);

        if (!$conference || !$amphitheatre) {
            throw $this->createNotFoundException('La conférence ou l\'amphithéâtre n\'existe pas.');
        }

        $conference->setRefAmphi($amphitheatre);
        $conference->setStatut(true);

        $entityManager->persist($conference);
        $entityManager->flush();

        $this->addFlash('success', 'L\'amphithéâtre a été lié à la conférence validée avec succès.');
        /*
        $email = (new Email())
            ->from('hsp.botadm@gmail.com') //email créer du bot projet (mdp :HSPpassword)
            ->to('c.gravallon@lprs.fr')
            ->subject('Votre conférence a été validée')
            ->text('Votre conférence a été validée avec succès.');

        $mailer->send($email);

*/

        $destinataire = "SELECT utilisateur.email FROM utilisateur JOIN conference ON utilisateur.id=conference.ref_utilisateur_id ";
        $sujet = "Confirmation de conference";
        $message = "Votre conference est validé avec succès";

// En-têtes additionnels
        $headers = "De: bot@hsp.com";

// Envoi de l'e-mail
        $mailEnvoye = mail($destinataire, $sujet, $message, $headers);

// Vérification si l'e-mail a été envoyé avec succès
        if ($mailEnvoye) {
            echo "L'e-mail a été envoyé avec succès.";
        } else {
            echo "Échec de l'envoi de l'e-mail. Veuillez vérifier la configuration de votre serveur.";
        }


        return $this->redirectToRoute('conference.index');
    }

    #[Route('/conference/invalidation/{id}', 'conference.invalidation', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function invaliderConference(
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

<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\RepresentantH;
use App\Entity\Utilisateur;
use App\Form\ProfilType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'utilisateur.index')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(
        UtilisateurRepository $repository,
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
        $utilisateur = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), 10
        );

        return $this->render('pages/utilisateur/index.html.twig', [
            'utilisateur' => $utilisateur
        ]);
    }

    #[Route('utilisateur/nouveau', name: 'utilisateur.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new (
        Request $request,
        EntityManagerInterface $manager) : Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $utilisateur = $form->getData();
            $utilisateur->setStatut(1);

            $manager->persist($utilisateur);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur à été créé avec succès !'
            );
            return $this->redirectToRoute('utilisateur.index');
        }

        return $this->render('pages/utilisateur/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/utilisateur/edition/{id}', name: 'utilisateur.edit', methods: ['GET', 'POST'])]
    public function edit(
        Utilisateur $utilisateur,
        Request $request,
        EntityManagerInterface $manager) : Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $utilisateur = $form->getData();

            $manager->persist($utilisateur);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur à été modifié avec succès !'
            );

            return $this->redirectToRoute('menu.index');
        }

        return $this->render('pages/utilisateur/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/utilisateur/profile', name: 'utilisateur.profile', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function showprofile(): Response
    {
        $user = $this->getUser();

        if ($user instanceof Etudiant) {
            $domaineEtude = $user->getDomaineEtude();
        } elseif ($user instanceof RepresentantH) {
            $nomHopital = $user->getNomHopital();
            $adresseHopital = $user->getAdresse();
            $roleDansHopital = $user->getRole();
        }

        $userId = $user->getId();

        return $this->render('pages/utilisateur/profile.html.twig', [
            'utilisateur' => $user,
            'domaineEtude' => $domaineEtude ?? null,
            'nomHopital' => $nomHopital ?? null,
            'adresseHopital' => $adresseHopital ?? null,
            'roleDansHopital' => $roleDansHopital ?? null,
            'userId' => $userId,
        ]);
    }

    #[Route('/utilisateur/edit-profile/{id}', name: 'utilisateur.edit_profile', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function editprofile(
        Utilisateur $user,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher): Response
    {
        $userRole = $this->getUser()->getRoles()[0];

        $form = $this->createForm(ProfilType::class, $user, ['user_role' => $userRole]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $password = $form->get('password')->getData();
            if ($password) {
                $encodedPassword = $hasher->hashPassword($user, $password);
                $user->setPassword($encodedPassword);
            }

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Vos informations ont été mises à jour avec succès.');
            return $this->redirectToRoute('utilisateur.profile');
        }

        return $this->render('pages/utilisateur/edit.html.twig', [
            'form' => $form->createView(),
            'user_role' => $userRole,
        ]);
    }

    #[Route('/utilisateur/validation/{id}', name: 'utilisateur.validation', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function validerUtilisateur(Utilisateur $utilisateur, EntityManagerInterface $manager): Response
    {
        $utilisateur->setStatut(true);
        $utilisateur->setRefAdmin($this->getUser());
        $manager->persist($utilisateur);
        $manager->flush();

        $this->addFlash(
            'success',
            'L\'utilisateur a été validé avec succès !'
        );

        return $this->redirectToRoute('utilisateur.index');
    }

    #[Route('/utilisateur/invalidation/{id}', name: 'utilisateur.invalidation', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function invaliderUtilisateur(Utilisateur $utilisateur, EntityManagerInterface $manager): Response
    {
        $utilisateur->setStatut(false);
        $utilisateur->setRefAdmin($this->getUser());
        $manager->persist($utilisateur);
        $manager->flush();

        $this->addFlash(
            'success',
            'L\'utilisateur a été invalidé avec succès !'
        );

        return $this->redirectToRoute('utilisateur.index');
    }
}

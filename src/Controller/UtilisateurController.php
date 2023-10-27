<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/utilisateur/edition/{id}', 'utilisateur.edit', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('utilisateur.index');
        }

        return $this->render('pages/utilisateur/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/utilisateur/validation/{id}', 'utilisateur.validation', methods: ['GET', 'POST'])]
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

    #[Route('/utilisateur/invalidation/{id}', 'utilisateur.invalidation', methods: ['GET', 'POST'])]
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

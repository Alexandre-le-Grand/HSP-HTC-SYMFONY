<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'utilisateur.index')]
    public function index(
        UtilisateurRepository $repository,
        PaginatorInterface $paginator,
        Request $request): Response
    {
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
}

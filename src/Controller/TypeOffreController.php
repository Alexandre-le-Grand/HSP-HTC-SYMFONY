<?php

namespace App\Controller;

use App\Entity\TypeOffre;
use App\Form\TypeOffreType;
use App\Repository\TypeOffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeOffreController extends AbstractController
{
    #[Route('/type_offre', name: 'type_offre.index')]
    public function index(
        TypeOffreRepository $repository,
        PaginatorInterface  $paginator,
        Request             $request): Response
    {
        // partie pour bloquer un utilisateur non autorisé
        $user = $this->getUser();
        $statut = $user->isStatut();

        if ($statut === false) {
            return $this->redirectToRoute('access_denied.index');
        }
        // --------------------------------------------------------------
        $type_offre = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('pages', 1), 10);

        return $this->render('pages/type_offre/index.html.twig', [
            'type_offre' => $type_offre,
        ]);
    }


    #[Route('/type_offre/nouveau', 'type_offre.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(
        Request $request,
        EntityManagerInterface $manager): Response
    {
        $type_offre = new TypeOffre();
        $form = $this->createForm(TypeOffreType::class, $type_offre);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $type_offre = $form->getData();

            $manager->persist($type_offre);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le type d\'offre à été créé avec succès !'
            );

            return $this->redirectToRoute('type_offre.index');
        }

        return $this->render('pages/type_offre/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/type_offre/edition/{id}', 'type_offre.edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(
        TypeOffre $type_offre,
        Request $request,
        EntityManagerInterface $manager) : Response
    {
        $form = $this->createForm(TypeOffreType::class, $type_offre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $type_offre = $form->getData();

            $manager->persist($type_offre);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le type d\'offre à été modifié avec succès !'
            );

            return $this->redirectToRoute('type_offre.index');
        }

        return $this->render('pages/type_offre/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('type_offre/suppression/{id}', 'type_offre.delete', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(
        EntityManagerInterface $manager,
        TypeOffre $type_offre) : Response
    {
        $manager->remove($type_offre);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le type d\'offre à été supprimé avec succès !'
        );

        return $this->redirectToRoute('type_offre.index');
    }
}

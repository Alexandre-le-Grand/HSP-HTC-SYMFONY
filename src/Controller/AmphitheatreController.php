<?php

namespace App\Controller;

use App\Entity\Amphitheatre;
use App\Form\AmphitheatreType;
use App\Repository\AmphitheatreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AmphitheatreController extends AbstractController
{
    /**
     * This function allow us to display all amphitheatres
     * @param AmphitheatreRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/amphitheatre', name: 'amphitheatre.index')]
    public function index(
        AmphitheatreRepository $repository,
        PaginatorInterface $paginator,
        Request $request): Response
    {
        $amphitheatre = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/amphitheatre/index.html.twig', [
            'amphitheatres' => $amphitheatre
        ]);
    }

    #[Route('/amphitheatre/nouveau', 'amphitheatre.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager) : Response
    {
        $amphitheatre = new Amphitheatre();
        $form = $this->createForm(AmphitheatreType::class, $amphitheatre);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $amphitheatre = $form->getData();

            $manager->persist($amphitheatre);
            $manager->flush();

            return $this->redirectToRoute('amphitheatre.index');
        }

        return $this->render('pages/amphitheatre/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

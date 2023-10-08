<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(
        ConferenceRepository $repository,
        PaginatorInterface $paginator,
        Request $request): Response
    {
        $conferences = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/conference/index.html.twig', [
            'conferences' => $conferences
        ]);
    }

    #[Route('/conference/nouveau', 'conference.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager) : Response
    {
        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $conference = $form->getData();

            $manager->persist($conference);
            $manager->flush();
        }

        return $this->render('pages/conference/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

}

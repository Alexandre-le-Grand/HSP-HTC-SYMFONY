<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\RepresentantH;
use App\Form\EtudiantType;
use App\Form\RepresentantHType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * This controller allow us to login
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/connexion', name: 'security.login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
       if ($this->getUser()){
           return $this->redirectToRoute('menu.index');
       }

        return $this->render('pages/security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }




    /**
     * This controller allow us to logout
     * @return void
     */
    #[Route('deconnexion', 'security.logout')]
    public function logout()
    {
        // Nothing to do here...
    }

    /**
     * This controller allow us to register
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/inscriptionEtudiant', 'security.inscriptionEtudiant', methods: ['GET', 'POST'])]
    public function inscriptionEtudiant(Request $request, EntityManagerInterface $manager) : Response
    {
        $etudiant = new Etudiant();
        $etudiant->setRoles(['ROLE_ETUDIANT']);
        $form = $this->createForm(EtudiantType::class, $etudiant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $etudiant = $form->getData();

            $this->addFlash(
                'success',
                'Votre compte à bien été créé'
            );

            $manager->persist($etudiant);
            $manager->flush();

            return $this->redirectToRoute('security.login');
        }

        return $this->render('pages/security/inscriptionEtudiant.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('/inscriptionRepresentant', 'security.inscriptionRepresentant', methods: ['GET', 'POST'])]
    public function inscriptionRepresentant(Request $request, EntityManagerInterface $manager) : Response
    {
        $representant = new RepresentantH();
        $representant->setRoles(['ROLE_REPRESENTANT']);
        $form = $this->createForm(RepresentantHType::class, $representant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $representant = $form->getData();

            $this->addFlash(
                'success',
                'Votre compte à bien été créé'
            );

            $manager->persist($representant);
            $manager->flush();

            return $this->redirectToRoute('security.login');
        }

        return $this->render('pages/security/inscriptionRepresentant.html.twig', [
            'form' => $form->createView()
        ]);

    }
}

<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
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
    #[Route('/inscription', 'security.inscription', methods: ['GET', 'POST'])]
    public function registration(Request $request, EntityManagerInterface $manager) : Response
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setRoles(['ROLE_USER']);
        $form = $this->createForm(InscriptionType::class, $utilisateur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = $form->getData();

            $this->addFlash(
                'success',
                'Votre compte à bien été créé'
            );

            $manager->persist($utilisateur);
            $manager->flush();

            return $this->redirectToRoute('security.login');
        }

        return $this->render('pages/security/inscription.html.twig', [
            'form' => $form->createView()
        ]);

    }
}

<?php

// src/Controller/ResetPasswordController.php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RestPassWordType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;

class ResetPassWordController extends AbstractController
{

    #[Route("/reset-password", name: "reset_password_request")]
    public function request(Request $request, TokenGeneratorInterface $tokenGenerator, MailerInterface $mailer): Response
    {
        $form = $this->createForm(RestPassWordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);

            if ($user) {
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->flush();

                $resetLink = $this->generateUrl('reset_password_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $email = (new Email())
                    ->from('noreply@example.com')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation de mot de passe')
                    ->html("Cliquez sur le lien suivant pour réinitialiser votre mot de passe : <a href='{$resetLink}'>Réinitialiser le mot de passe</a>");

                $mailer->send($email);

                return $this->redirectToRoute('reset_password_request_success');
            }
        }

        return $this->render('reset_password/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/reset-password/request-success", name: "reset_password_request_success")]
    public function requestSuccess(): Response
    {
        return $this->render('reset_password/request_success.html.twig');
    }

    #[Route("/reset-password/reset/{token}", name: "reset_password_reset")]
    public function reset(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['resetToken' => $token]);

        if (!$user) {
            return $this->redirectToRoute('reset_password_invalid_token');
        }

        if ($request->isMethod('POST')) {
            $user->setResetToken(null);
            $newPassword = $request->request->get('new_password');
            $encodedPassword = $passwordEncoder->encodePassword($user, $newPassword);
            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reset_password_success');
        }

        return $this->render('reset_password/reset.html.twig', ['token' => $token]);
    }

    #[Route("/reset-password/success", name: "reset_password_success")]
    public function success(): Response
    {
        return $this->render('reset_password/success.html.twig');
    }

    #[Route("/reset-password/invalid-token", name: "reset_password_invalid_token")]
    public function invalidToken(): Response
    {
        return $this->render('reset_password/invalid_token.html.twig');
    }

    private $doctrine;

    public function __construct(ManagerRegistry $registry)
    {
        $this->doctrine = $registry;
    }

    private function getDoctrine()
    {
        return $this->doctrine;
    }
}

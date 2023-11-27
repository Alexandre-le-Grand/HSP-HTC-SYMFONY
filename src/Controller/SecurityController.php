<?php

namespace App\Controller;


use App\Entity\Etudiant;
use App\Entity\RepresentantH;
use App\Form\EtudiantType;
use App\Form\RepresentantHType;
use App\Form\ResetPasswordFormType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
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


    #[Route('/oubli-pass', name:'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UtilisateurRepository $usersRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response
    {
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
        //$test = $usersRepository->findOneByEmail("bertrand.hubert@delannoy.org");


        if($form->isSubmitted() && $form->isValid()){
            //On va chercher l'utilisateur par son email
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());

            // On vérifie si on a un utilisateur
            if($user){
                // On génère un token de réinitialisation
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // On génère un lien de réinitialisation du mot de passe
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // On crée les données du mail
                $context = compact('url', 'user');

                // Envoi du mail
                $mailer->send(
                    (new TemplatedEmail())
                        ->from('no-reply@e-commerce.fr')
                        ->to($user->getEmail())
                        ->subject('Réinitialisation de mot de passe')
                        ->htmlTemplate('pages/reset_password/reset_password_request.html.twig')
                        ->context(['url' => $url, 'user' => $user])
                );

                $this->addFlash('success', 'Email envoyé avec succès');
                return $this->redirectToRoute('security.login');
            }
            // $user est null
            $this->addFlash('danger', 'Un problème est survenu');
            return $this->redirectToRoute('security.login');
        }

        var_dump($form);
        return $this->render('pages/reset_password/reset_password_request.html.twig', [
            'test' => $form->createView(),
            //'test' => $test,
        ]);
    }
    #[Route('/oubli-pass/{token}', name:'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        UtilisateurRepository $usersRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // On vérifie si on a ce token dans la base
        $user = $usersRepository->findOneByResetToken($token);

        // On vérifie si l'utilisateur existe

        if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // On efface le token
                $user->setResetToken('');


// On enregistre le nouveau mot de passe en le hashant
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('security.login');
            }

            return $this->render('reset_password/reset_password.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        // Si le token est invalide on redirige vers le login
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('security.login');
    }

}

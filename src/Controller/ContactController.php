<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact.index')]
    public function index(
        Request $request,
        EntityManagerInterface $manager,
        MailerInterface $mailer): Response
    {
        $contact = new Contact();

        if ($this->getUser()) {
            $contact->setNom($this->getUser()->getNom());
            $contact->setPrenom($this->getUser()->getPrenom());
            $contact->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();

            $manager->persist($contact);
            $manager->flush();

            //Email
            $email = (new TemplatedEmail())
                ->from($contact->getEmail())
                ->to('admin@hsp.com')
                ->subject($contact->getSubject())
                ->htmlTemplate('emails/contact.html.twig')

                // pass variables (name => value) to the template
                ->context([
                    'contact' => $contact
                ]);

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre demande à été envoyée avec succès !'
            );
        }

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

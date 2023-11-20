<?php


namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $to, string $subject, string $body)
    {
        $email = (new Email())
            ->from('votre_adresse_email@example.com')
            ->to($to)
            ->subject($subject)
            ->text($body);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new \Exception('Erreur lors de l\'envoi de l\'e-mail : ' . $e->getMessage());
        }
    }
}
?>
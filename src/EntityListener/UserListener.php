<?php

namespace App\EntityListener;

use App\Entity\Utilisateur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function prePersist(Utilisateur $utilisateur)
    {
        $this->encodePassword($utilisateur);
    }

    public function preUpdate(Utilisateur $utilisateur)
    {
        $this->encodePassword($utilisateur);
    }

    /**
     * Encode Password based on plain password
     * @param Utilisateur $utilisateur
     * @return void
     */
    public function encodePassword(Utilisateur $utilisateur)
    {
        if ($utilisateur->getPlainpassword() === null) {
            return;
        }

        $utilisateur->setPassword(
            $this->hasher->hashPassword(
                $utilisateur,
                $utilisateur->getPlainpassword()
            )
        );
    }
}
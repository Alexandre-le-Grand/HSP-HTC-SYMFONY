<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i<25; $i++) {
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($this->faker->word());
            $utilisateur->setPrenom($this->faker->firstname());
            $utilisateur->setEmail($this->faker->email());
            $utilisateur->setStatut($this->faker->boolean());
            $utilisateur->setRoles(['ROLE_USER']);
            $utilisateur->setPlainpassword('password');


            $manager->persist($utilisateur);
        }


        $manager->flush();
    }
}
<?php

namespace App\DataFixtures;

use App\Entity\Administrateur;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{

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
            $utilisateur->setMdp($this->faker->password());
            $utilisateur->setStatut($this->faker->boolean());
            //$utilisateur->setRefAdmin($this->faker->boolean());

            $manager->persist($utilisateur);
        }
        for ($i=0; $i<25; $i++) {
            $administrateur = new Administrateur();
            $administrateur->setNom($this->faker->word());
            $administrateur->setPrenom($this->faker->firstname());
            $administrateur->setEmail($this->faker->email());
            $administrateur->setMdp($this->faker->password());
            $administrateur->setStatut($this->faker->boolean());
           // $administrateur->setRefAdmin($this->faker->boolean());

            $manager->persist($administrateur);
        }

        $manager->flush();
    }

}

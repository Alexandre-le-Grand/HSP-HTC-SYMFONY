<?php

namespace App\DataFixtures;

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
        for ($i=0; $i<50; $i++) {
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($this->faker->word());

            $manager->persist($utilisateur);
        }

        $manager->flush();
    }
}

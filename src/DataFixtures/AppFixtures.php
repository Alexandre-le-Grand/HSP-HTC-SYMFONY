<?php

namespace App\DataFixtures;

use App\Entity\Amphitheatre;
use App\Entity\Conference;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

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
            $this->addReference('utilisateur_id' . $i, $utilisateur);
        }

        for ($i=0; $i<25; $i++) {
            $amphitheatre = new Amphitheatre();
            $amphitheatre->setNom($this->faker->word());
            $amphitheatre->setNbPlaces($this->faker->randomNumber(3));

            $manager->persist($amphitheatre);
            $this->addReference('amphitheatre_id' . $i, $amphitheatre);
        }


        for ($i=0; $i<25; $i++) {
            $conference = new Conference();
            $conference->setNom($this->faker->word());
            $conference->setDescription($this->faker->sentence());

            $date = new \DateTime('now');
            $dateImmutable = \DateTimeImmutable::createFromMutable($date);
            $conference->setDate($dateImmutable);

            $heure = new \DateTime('now');
            $heureImmutable = \DateTimeImmutable::createFromMutable($heure);
            $conference->setHeure($heureImmutable);

            $interval = $this->faker->dateTimeBetween('-5 hours', '5 hours');
            $conference->setDuree($interval->diff($heure));

            $conference->setStatut(false);

            $utilisateurReference = $this->getReference('utilisateur_id' . $i % 25);
            $conference->setRefUtilisateur($utilisateurReference);
            $amphitheatreReference = $this->getReference('amphitheatre_id' . $i % 10);
            $conference->setRefAmphi($amphitheatreReference);

            $manager->persist($conference);
        }


        $manager->flush();
    }
}
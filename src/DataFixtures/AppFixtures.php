<?php

namespace App\DataFixtures;

use App\Entity\Administrateur;
use App\Entity\Amphitheatre;
use App\Entity\Conference;
use App\Entity\Etudiant;
use App\Entity\OffreEmploi;
use App\Entity\Postulation;
use App\Entity\RepresentantH;
use App\Entity\TypeOffre;
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
        for ($i = 0; $i < 5; $i++) {
            $admin = new Administrateur();
            $admin->setNom($this->faker->lastName);
            $admin->setPrenom($this->faker->firstname());
            $admin->setEmail($this->faker->email());
            $admin->setStatut(1);
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setPlainpassword('password');
            $admin->setResetToken(null);

            // Pour le premier administrateur, pas de référence à un autre administrateur
            if ($i > 0) {
                $validePar = $this->getReference('admin_id' . ($i - 1));
                $admin->setRefAdmin($validePar);
            }

            $manager->persist($admin);
            $this->addReference('admin_id' . $i, $admin);
        }

        for ($i = 0; $i < 10; $i++) {
            $etudiant = new Etudiant();
            $etudiant->setNom($this->faker->lastName);
            $etudiant->setPrenom($this->faker->firstname());
            $etudiant->setEmail($this->faker->email());
            $etudiant->setStatut($this->faker->boolean());
            $etudiant->setRoles(['ROLE_ETUDIANT']);
            $etudiant->setPlainpassword('password');
            $domainesEtude = ['Chirurgie', 'Pédiatrie','Cardiologie','Dermatologie','Ophtalmologie','Neurologie','Radiologie','Psychiatrie','Orthopédie', 'Gastro-entérologie','Urologie','Néphrologie','Endocrinologie','Rhumatologie','Hématologie', 'Oncologie','Infectiologie' ,'Anesthésiologie'];
            $etudiant->setDomaineEtude($this->faker->randomElement($domainesEtude));
            $etudiant->setResetToken(null);

            $manager->persist($etudiant);
            $this->addReference('etudiant_id' . $i, $etudiant);
        }

        for ($i = 0; $i < 10; $i++) {
            $representant = new RepresentantH();
            $representant->setNom($this->faker->lastName);
            $representant->setPrenom($this->faker->firstname());
            $representant->setEmail($this->faker->email());
            $representant->setStatut($this->faker->boolean());
            $representant->setRoles(['ROLE_REPRESENTANT']);
            $representant->setPlainpassword('password');
            $representant->setNomHopital($this->faker->lastName);
            $representant->setAdresse($this->faker->address);
            $representant->setRole($this->faker->jobTitle);
            $representant->setResetToken(null);

            $manager->persist($representant);
            $this->addReference('representant_id' . $i, $representant);
        }

        for ($i=0; $i < 10; $i++) {
            $amphitheatre = new Amphitheatre();
            $amphitheatre->setNom($this->faker->lastName);
            $amphitheatre->setNbPlaces($this->faker->randomNumber(3));

            $manager->persist($amphitheatre);
            $this->addReference('amphitheatre_id' . $i, $amphitheatre);
        }


        for ($i=0; $i < 10; $i++) {
            $conference = new Conference();
            $conference->setNom($this->faker->word());
            $conference->setDescription($this->faker->sentence());

            $date = $this->faker->dateTimeBetween('now', '+2 years');
            $dateStr = $date->format('Y-m-d');
            $conference->setDate($dateStr);

            $heure = $this->faker->dateTimeBetween('08:00', '12:00');
            $heureStr = $heure->format('H:i');
            $conference->setHeure($heureStr);

            $dureeHours = $this->faker->numberBetween(1, 5); // Par exemple, de 1 à 5 heures
            $dureeMinutes = $this->faker->numberBetween(0, 59); // De 0 à 59 minutes
            $duree = sprintf('%02d:%02d', $dureeHours, $dureeMinutes);
            $conference->setDuree($duree);

            $conference->setStatut(false);

            $utilisateurReference = $this->getReference('admin_id' . $i % 5);
            $conference->setRefUtilisateur($utilisateurReference);
            $amphitheatreReference = $this->getReference('amphitheatre_id' . $i % 10);
            $conference->setRefAmphi($amphitheatreReference);

            $manager->persist($conference);
        }

        $typesOffre = ['CDD', 'CDI', 'Stage', 'Alternance', 'Intérim', 'Freelance'];

        foreach ($typesOffre as $i => $libelle) {
            $type_offre = new TypeOffre();
            $type_offre->setLibelle($libelle);
            $manager->persist($type_offre);
            $this->addReference('type_offre_id' . $i, $type_offre);

            $offre = new OffreEmploi();
            $offre->setTitre($this->faker->jobTitle);
            $offre->setDescription($this->faker->text(30));
            $typeContratReference = $this->getReference('type_offre_id' . $i);
            $offre->setTypeContrat($typeContratReference);
            $representantReference = $this->getReference('representant_id' . $i);
            $offre->setRefRepresentantH($representantReference);
            $manager->persist($offre);

            // Générer 5 postulations pour chaque offre d'emploi avec un étudiant au hasard
            $etudiant = $this->getReference('etudiant_id' . $i);
            $postedStudents = [];

            for ($j = 0; $j < 5; $j++) {
                // Vérifier si l'étudiant a déjà postulé à cette offre d'emploi
                if (!in_array($etudiant, $postedStudents)) {
                    $postedStudents[] = $etudiant;

                    $postulation = new Postulation();
                    $postulation->setRefEtudiant($etudiant);
                    $postulation->setRefOffre($offre);
                    $manager->persist($postulation);
                }
            }
        }

        $manager->flush();
    }
}
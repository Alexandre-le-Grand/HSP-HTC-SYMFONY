<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002102404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, mdp VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE amphitheatre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, nb_places INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conference (id INT AUTO_INCREMENT NOT NULL, ref_admin_id INT DEFAULT NULL, ref_representant_h_id INT DEFAULT NULL, ref_amphi_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', heure TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', duree VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', statut TINYINT(1) NOT NULL, INDEX IDX_911533C8E23C4497 (ref_admin_id), INDEX IDX_911533C86A0FC1D8 (ref_representant_h_id), INDEX IDX_911533C8685B0108 (ref_amphi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE creation (id INT AUTO_INCREMENT NOT NULL, ref_etudiant_id INT DEFAULT NULL, ref_representant_h_id INT DEFAULT NULL, ref_conference_id INT NOT NULL, INDEX IDX_57EE857427E3492F (ref_etudiant_id), INDEX IDX_57EE85746A0FC1D8 (ref_representant_h_id), UNIQUE INDEX UNIQ_57EE8574441BD4E2 (ref_conference_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, ref_admin_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, mdp VARCHAR(255) NOT NULL, domaine_etude VARCHAR(255) NOT NULL, INDEX IDX_717E22E3E23C4497 (ref_admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, ref_conference_id INT NOT NULL, INDEX IDX_5E90F6D6441BD4E2 (ref_conference_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription_etudiant (inscription_id INT NOT NULL, etudiant_id INT NOT NULL, INDEX IDX_D8EB5D465DAC5993 (inscription_id), INDEX IDX_D8EB5D46DDEAB1A3 (etudiant_id), PRIMARY KEY(inscription_id, etudiant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_emploi (id INT AUTO_INCREMENT NOT NULL, ref_representant_h_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, type_contrat VARCHAR(255) NOT NULL, INDEX IDX_132AD0D16A0FC1D8 (ref_representant_h_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postulation (id INT AUTO_INCREMENT NOT NULL, ref_offre_id INT NOT NULL, ref_etudiant_id INT NOT NULL, INDEX IDX_DA7D4E9BCADF96DD (ref_offre_id), INDEX IDX_DA7D4E9B27E3492F (ref_etudiant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, ref_representant_h_id INT NOT NULL, ref_etudiant_id INT NOT NULL, ref_offre_id INT NOT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', heure TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', statut TINYINT(1) NOT NULL, INDEX IDX_65E8AA0A6A0FC1D8 (ref_representant_h_id), INDEX IDX_65E8AA0A27E3492F (ref_etudiant_id), INDEX IDX_65E8AA0ACADF96DD (ref_offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE representant_h (id INT AUTO_INCREMENT NOT NULL, ref_admin_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, mdp VARCHAR(255) NOT NULL, nom_hopital VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_34AA5D80E23C4497 (ref_admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, mdp VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conference ADD CONSTRAINT FK_911533C8E23C4497 FOREIGN KEY (ref_admin_id) REFERENCES administrateur (id)');
        $this->addSql('ALTER TABLE conference ADD CONSTRAINT FK_911533C86A0FC1D8 FOREIGN KEY (ref_representant_h_id) REFERENCES representant_h (id)');
        $this->addSql('ALTER TABLE conference ADD CONSTRAINT FK_911533C8685B0108 FOREIGN KEY (ref_amphi_id) REFERENCES amphitheatre (id)');
        $this->addSql('ALTER TABLE creation ADD CONSTRAINT FK_57EE857427E3492F FOREIGN KEY (ref_etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE creation ADD CONSTRAINT FK_57EE85746A0FC1D8 FOREIGN KEY (ref_representant_h_id) REFERENCES representant_h (id)');
        $this->addSql('ALTER TABLE creation ADD CONSTRAINT FK_57EE8574441BD4E2 FOREIGN KEY (ref_conference_id) REFERENCES conference (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3E23C4497 FOREIGN KEY (ref_admin_id) REFERENCES administrateur (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6441BD4E2 FOREIGN KEY (ref_conference_id) REFERENCES conference (id)');
        $this->addSql('ALTER TABLE inscription_etudiant ADD CONSTRAINT FK_D8EB5D465DAC5993 FOREIGN KEY (inscription_id) REFERENCES inscription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscription_etudiant ADD CONSTRAINT FK_D8EB5D46DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D16A0FC1D8 FOREIGN KEY (ref_representant_h_id) REFERENCES representant_h (id)');
        $this->addSql('ALTER TABLE postulation ADD CONSTRAINT FK_DA7D4E9BCADF96DD FOREIGN KEY (ref_offre_id) REFERENCES offre_emploi (id)');
        $this->addSql('ALTER TABLE postulation ADD CONSTRAINT FK_DA7D4E9B27E3492F FOREIGN KEY (ref_etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6A0FC1D8 FOREIGN KEY (ref_representant_h_id) REFERENCES representant_h (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A27E3492F FOREIGN KEY (ref_etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0ACADF96DD FOREIGN KEY (ref_offre_id) REFERENCES offre_emploi (id)');
        $this->addSql('ALTER TABLE representant_h ADD CONSTRAINT FK_34AA5D80E23C4497 FOREIGN KEY (ref_admin_id) REFERENCES administrateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conference DROP FOREIGN KEY FK_911533C8E23C4497');
        $this->addSql('ALTER TABLE conference DROP FOREIGN KEY FK_911533C86A0FC1D8');
        $this->addSql('ALTER TABLE conference DROP FOREIGN KEY FK_911533C8685B0108');
        $this->addSql('ALTER TABLE creation DROP FOREIGN KEY FK_57EE857427E3492F');
        $this->addSql('ALTER TABLE creation DROP FOREIGN KEY FK_57EE85746A0FC1D8');
        $this->addSql('ALTER TABLE creation DROP FOREIGN KEY FK_57EE8574441BD4E2');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3E23C4497');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6441BD4E2');
        $this->addSql('ALTER TABLE inscription_etudiant DROP FOREIGN KEY FK_D8EB5D465DAC5993');
        $this->addSql('ALTER TABLE inscription_etudiant DROP FOREIGN KEY FK_D8EB5D46DDEAB1A3');
        $this->addSql('ALTER TABLE offre_emploi DROP FOREIGN KEY FK_132AD0D16A0FC1D8');
        $this->addSql('ALTER TABLE postulation DROP FOREIGN KEY FK_DA7D4E9BCADF96DD');
        $this->addSql('ALTER TABLE postulation DROP FOREIGN KEY FK_DA7D4E9B27E3492F');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A6A0FC1D8');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A27E3492F');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0ACADF96DD');
        $this->addSql('ALTER TABLE representant_h DROP FOREIGN KEY FK_34AA5D80E23C4497');
        $this->addSql('DROP TABLE administrateur');
        $this->addSql('DROP TABLE amphitheatre');
        $this->addSql('DROP TABLE conference');
        $this->addSql('DROP TABLE creation');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('DROP TABLE inscription_etudiant');
        $this->addSql('DROP TABLE offre_emploi');
        $this->addSql('DROP TABLE postulation');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE representant_h');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

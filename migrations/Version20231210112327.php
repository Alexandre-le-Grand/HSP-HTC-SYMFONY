<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231210112327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrateur (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE amphitheatre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(127) NOT NULL, nb_places INT NOT NULL, disponible TINYINT(1) DEFAULT NULL, heure_fin VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conference (id INT AUTO_INCREMENT NOT NULL, ref_utilisateur_id INT NOT NULL, ref_amphi_id INT DEFAULT NULL, nom VARCHAR(127) NOT NULL, description VARCHAR(127) NOT NULL, date VARCHAR(255) NOT NULL, heure VARCHAR(255) NOT NULL, duree VARCHAR(5) NOT NULL, statut TINYINT(1) NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_911533C8B61ED040 (ref_utilisateur_id), INDEX IDX_911533C8685B0108 (ref_amphi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) DEFAULT NULL, prenom VARCHAR(50) DEFAULT NULL, email VARCHAR(180) NOT NULL, subject VARCHAR(100) DEFAULT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT NOT NULL, domaine_etude VARCHAR(127) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, ref_etudiant_id INT NOT NULL, ref_conference_id INT NOT NULL, INDEX IDX_5E90F6D627E3492F (ref_etudiant_id), INDEX IDX_5E90F6D6441BD4E2 (ref_conference_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_emploi (id INT AUTO_INCREMENT NOT NULL, type_contrat_id INT DEFAULT NULL, ref_representant_h_id INT NOT NULL, titre VARCHAR(127) NOT NULL, description VARCHAR(127) NOT NULL, INDEX IDX_132AD0D1520D03A (type_contrat_id), INDEX IDX_132AD0D16A0FC1D8 (ref_representant_h_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postulation (id INT AUTO_INCREMENT NOT NULL, ref_offre_id INT NOT NULL, ref_etudiant_id INT NOT NULL, INDEX IDX_DA7D4E9BCADF96DD (ref_offre_id), INDEX IDX_DA7D4E9B27E3492F (ref_etudiant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, ref_representant_h_id INT NOT NULL, ref_etudiant_id INT NOT NULL, ref_offre_id INT NOT NULL, postulation_id INT NOT NULL, date VARCHAR(255) NOT NULL, heure VARCHAR(255) NOT NULL, statut TINYINT(1) NOT NULL, INDEX IDX_65E8AA0A6A0FC1D8 (ref_representant_h_id), INDEX IDX_65E8AA0A27E3492F (ref_etudiant_id), INDEX IDX_65E8AA0ACADF96DD (ref_offre_id), INDEX IDX_65E8AA0AD749FDF1 (postulation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE representant_h (id INT NOT NULL, nom_hopital VARCHAR(127) NOT NULL, adresse VARCHAR(127) NOT NULL, role VARCHAR(127) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_offre (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(127) NOT NULL, UNIQUE INDEX UNIQ_A18A0198A4D60759 (libelle), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, ref_admin_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(180) NOT NULL, statut TINYINT(1) NOT NULL, password VARCHAR(255) NOT NULL, reset_token VARCHAR(100) DEFAULT NULL, roles JSON NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), INDEX IDX_1D1C63B3E23C4497 (ref_admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE administrateur ADD CONSTRAINT FK_32EB52E8BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conference ADD CONSTRAINT FK_911533C8B61ED040 FOREIGN KEY (ref_utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE conference ADD CONSTRAINT FK_911533C8685B0108 FOREIGN KEY (ref_amphi_id) REFERENCES amphitheatre (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D627E3492F FOREIGN KEY (ref_etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6441BD4E2 FOREIGN KEY (ref_conference_id) REFERENCES conference (id)');
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D1520D03A FOREIGN KEY (type_contrat_id) REFERENCES type_offre (id)');
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D16A0FC1D8 FOREIGN KEY (ref_representant_h_id) REFERENCES representant_h (id)');
        $this->addSql('ALTER TABLE postulation ADD CONSTRAINT FK_DA7D4E9BCADF96DD FOREIGN KEY (ref_offre_id) REFERENCES offre_emploi (id)');
        $this->addSql('ALTER TABLE postulation ADD CONSTRAINT FK_DA7D4E9B27E3492F FOREIGN KEY (ref_etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6A0FC1D8 FOREIGN KEY (ref_representant_h_id) REFERENCES representant_h (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A27E3492F FOREIGN KEY (ref_etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0ACADF96DD FOREIGN KEY (ref_offre_id) REFERENCES offre_emploi (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AD749FDF1 FOREIGN KEY (postulation_id) REFERENCES postulation (id)');
        $this->addSql('ALTER TABLE representant_h ADD CONSTRAINT FK_34AA5D80BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3E23C4497 FOREIGN KEY (ref_admin_id) REFERENCES administrateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur DROP FOREIGN KEY FK_32EB52E8BF396750');
        $this->addSql('ALTER TABLE conference DROP FOREIGN KEY FK_911533C8B61ED040');
        $this->addSql('ALTER TABLE conference DROP FOREIGN KEY FK_911533C8685B0108');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3BF396750');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D627E3492F');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6441BD4E2');
        $this->addSql('ALTER TABLE offre_emploi DROP FOREIGN KEY FK_132AD0D1520D03A');
        $this->addSql('ALTER TABLE offre_emploi DROP FOREIGN KEY FK_132AD0D16A0FC1D8');
        $this->addSql('ALTER TABLE postulation DROP FOREIGN KEY FK_DA7D4E9BCADF96DD');
        $this->addSql('ALTER TABLE postulation DROP FOREIGN KEY FK_DA7D4E9B27E3492F');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A6A0FC1D8');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A27E3492F');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0ACADF96DD');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AD749FDF1');
        $this->addSql('ALTER TABLE representant_h DROP FOREIGN KEY FK_34AA5D80BF396750');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3E23C4497');
        $this->addSql('DROP TABLE administrateur');
        $this->addSql('DROP TABLE amphitheatre');
        $this->addSql('DROP TABLE conference');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('DROP TABLE offre_emploi');
        $this->addSql('DROP TABLE postulation');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE representant_h');
        $this->addSql('DROP TABLE type_offre');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

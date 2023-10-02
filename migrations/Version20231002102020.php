<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002102020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant ADD ref_admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3E23C4497 FOREIGN KEY (ref_admin_id) REFERENCES administrateur (id)');
        $this->addSql('CREATE INDEX IDX_717E22E3E23C4497 ON etudiant (ref_admin_id)');
        $this->addSql('ALTER TABLE representant_h ADD ref_admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE representant_h ADD CONSTRAINT FK_34AA5D80E23C4497 FOREIGN KEY (ref_admin_id) REFERENCES administrateur (id)');
        $this->addSql('CREATE INDEX IDX_34AA5D80E23C4497 ON representant_h (ref_admin_id)');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3E23C4497');
        $this->addSql('DROP INDEX IDX_1D1C63B3E23C4497 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP ref_admin_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3E23C4497');
        $this->addSql('DROP INDEX IDX_717E22E3E23C4497 ON etudiant');
        $this->addSql('ALTER TABLE etudiant DROP ref_admin_id');
        $this->addSql('ALTER TABLE representant_h DROP FOREIGN KEY FK_34AA5D80E23C4497');
        $this->addSql('DROP INDEX IDX_34AA5D80E23C4497 ON representant_h');
        $this->addSql('ALTER TABLE representant_h DROP ref_admin_id');
        $this->addSql('ALTER TABLE utilisateur ADD ref_admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3E23C4497 FOREIGN KEY (ref_admin_id) REFERENCES administrateur (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B3E23C4497 ON utilisateur (ref_admin_id)');
    }
}

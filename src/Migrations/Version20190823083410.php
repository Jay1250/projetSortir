<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190823083410 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lieux CHANGE villes_no_ville villes_no_ville INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participants CHANGE sites_no_site sites_no_site INT DEFAULT NULL, CHANGE mot_de_passe mot_de_passe VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE sorties ADD villes_no_ville INT DEFAULT NULL, CHANGE organisateur organisateur INT DEFAULT NULL, CHANGE lieux_no_lieu lieux_no_lieu INT DEFAULT NULL, CHANGE etats_no_etat etats_no_etat INT DEFAULT NULL, CHANGE sorties_no_sortie sorties_no_sortie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sorties ADD CONSTRAINT FK_488163E8395FAFC3 FOREIGN KEY (villes_no_ville) REFERENCES villes (no_ville)');
        $this->addSql('CREATE INDEX IDX_488163E8395FAFC3 ON sorties (villes_no_ville)');
        $this->addSql('ALTER TABLE inscriptions DROP date_inscription');
        $this->addSql('ALTER TABLE inscriptions RENAME INDEX inscriptions_participants_fk TO IDX_74E0281CEF759E07');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE inscriptions ADD date_inscription DATETIME NOT NULL');
        $this->addSql('ALTER TABLE inscriptions RENAME INDEX idx_74e0281cef759e07 TO inscriptions_participants_fk');
        $this->addSql('ALTER TABLE lieux CHANGE villes_no_ville villes_no_ville INT NOT NULL');
        $this->addSql('ALTER TABLE participants CHANGE sites_no_site sites_no_site INT NOT NULL, CHANGE mot_de_passe mot_de_passe VARCHAR(100) NOT NULL COLLATE utf8_general_ci');
        $this->addSql('ALTER TABLE sorties DROP FOREIGN KEY FK_488163E8395FAFC3');
        $this->addSql('DROP INDEX IDX_488163E8395FAFC3 ON sorties');
        $this->addSql('ALTER TABLE sorties DROP villes_no_ville, CHANGE etats_no_etat etats_no_etat INT NOT NULL, CHANGE lieux_no_lieu lieux_no_lieu INT NOT NULL, CHANGE organisateur organisateur INT NOT NULL, CHANGE sorties_no_sortie sorties_no_sortie INT NOT NULL');
    }
}

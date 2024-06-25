<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625090211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE events_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE events (id INT NOT NULL, patient_id INT NOT NULL, value TEXT NOT NULL, type VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5387574A6B899279 ON events (patient_id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE events_id_seq CASCADE');
        $this->addSql('ALTER TABLE events DROP CONSTRAINT FK_5387574A6B899279');
        $this->addSql('DROP TABLE events');
    }
}

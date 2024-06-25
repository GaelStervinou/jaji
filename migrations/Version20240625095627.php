<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625095627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE diagnostic_mental_health_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE diagnostic_risks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE diagnostic_mental_health (id INT NOT NULL, patient_id INT NOT NULL, value INT NOT NULL, content TEXT NOT NULL, reasons TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5FB311F06B899279 ON diagnostic_mental_health (patient_id)');
        $this->addSql('CREATE TABLE diagnostic_risks (id INT NOT NULL, patient_id INT NOT NULL, value INT NOT NULL, content TEXT NOT NULL, reasons TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B10703256B899279 ON diagnostic_risks (patient_id)');
        $this->addSql('ALTER TABLE diagnostic_mental_health ADD CONSTRAINT FK_5FB311F06B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE diagnostic_risks ADD CONSTRAINT FK_B10703256B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE diagnostic_mental_health_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE diagnostic_risks_id_seq CASCADE');
        $this->addSql('ALTER TABLE diagnostic_mental_health DROP CONSTRAINT FK_5FB311F06B899279');
        $this->addSql('ALTER TABLE diagnostic_risks DROP CONSTRAINT FK_B10703256B899279');
        $this->addSql('DROP TABLE diagnostic_mental_health');
        $this->addSql('DROP TABLE diagnostic_risks');
    }
}

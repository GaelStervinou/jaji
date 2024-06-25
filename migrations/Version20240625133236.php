<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625133236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE diagnostic_mental_health ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN diagnostic_mental_health.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE diagnostic_risks ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN diagnostic_risks.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE messages ALTER created_at SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE diagnostic_risks DROP created_at');
        $this->addSql('ALTER TABLE messages ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE diagnostic_mental_health DROP created_at');
    }
}

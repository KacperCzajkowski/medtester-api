<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220120084642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tests_templates (
            id UUID NOT NULL, 
            name TEXT NOT NULL, 
            icd_code TEXT NOT NULL, 
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
            results JSONB NOT NULL, 
            PRIMARY KEY(id))
        ');
        $this->addSql('COMMENT ON COLUMN tests_templates.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN tests_templates.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tests_templates.results IS \'(DC2Type:indicators_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tests_templates');
    }
}

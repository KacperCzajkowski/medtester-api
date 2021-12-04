<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211204232303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding tests result table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tests_results (
            id UUID NOT NULL, 
            user_id UUID NOT NULL, 
            laboratory_worker_id UUID NOT NULL, 
            status VARCHAR(255) NOT NULL, 
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
            results JSON NOT NULL, 
            PRIMARY KEY(id))
        ');
        $this->addSql('COMMENT ON COLUMN tests_results.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN tests_results.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN tests_results.laboratory_worker_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN tests_results.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tests_results.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tests_results.results IS \'(DC2Type:json_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tests_results');
    }
}

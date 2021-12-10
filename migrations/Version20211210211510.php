<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211210211510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tests_results ALTER results TYPE JSONB');
        $this->addSql('ALTER TABLE tests_results ALTER results DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN tests_results.results IS \'(DC2Type:result_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tests_results ALTER results TYPE JSON');
        $this->addSql('ALTER TABLE tests_results ALTER results DROP DEFAULT');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211201133949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding column about removing user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD removed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN users.removed_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users DROP removed_at');
    }
}

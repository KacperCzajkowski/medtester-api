<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211201122421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding column for cancelling activation link';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users_activation ADD cancelled_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN users_activation.cancelled_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users_activation DROP cancelled_at');
    }
}

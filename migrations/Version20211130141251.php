<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211130141251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user activation table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users_activation (
            id UUID NOT NULL, 
            user_id UUID NOT NULL, 
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
            used_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
            PRIMARY KEY(id))
        ');
        $this->addSql('COMMENT ON COLUMN users_activation.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users_activation.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users_activation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users_activation.used_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users_activation');
    }
}

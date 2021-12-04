<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211202191324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding laboratories table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE laboratories (
            id UUID NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
            created_by UUID NOT NULL, 
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
            updated_by UUID NOT NULL, 
            PRIMARY KEY(id))
        ');
        $this->addSql('COMMENT ON COLUMN laboratories.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN laboratories.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN laboratories.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN laboratories.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN laboratories.updated_by IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE laboratories');
    }
}

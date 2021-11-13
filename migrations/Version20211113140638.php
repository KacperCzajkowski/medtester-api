<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211113140638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating users table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (
            id UUID NOT NULL, 
            email VARCHAR(320) NOT NULL, 
            password VARCHAR(255) NOT NULL, 
            first_name VARCHAR(255) NOT NULL, 
            last_name VARCHAR(255) NOT NULL, 
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
            created_by UUID NOT NULL, 
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
            updated_by UUID NOT NULL, 
            roles JSON NOT NULL, 
            pesel CHAR(11) NOT NULL, 
            gender VARCHAR(255) NOT NULL, 
            laboratory_id UUID DEFAULT NULL, 
            PRIMARY KEY(id))
        ');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.email IS \'(DC2Type:email)\'');
        $this->addSql('COMMENT ON COLUMN users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.roles IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN users.pesel IS \'(DC2Type:pesel)\'');
        $this->addSql('COMMENT ON COLUMN users.laboratory_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}

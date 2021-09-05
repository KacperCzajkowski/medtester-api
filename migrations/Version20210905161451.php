<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210905161451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'adding users table';
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
            role VARCHAR(255) NOT NULL, 
            pesel CHAR(11) DEFAULT NULL, 
            gender VARCHAR(255) DEFAULT NULL, 
            laboratory_id UUID DEFAULT NULL, 
            PRIMARY KEY(id))
        ');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}

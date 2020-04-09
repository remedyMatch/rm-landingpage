<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200407093640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_7D3656A4E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__account AS SELECT id, firstname, lastname, email, street, housenumber, zipcode, city, phone, type, company, token, verified_at, created_at, reviewed_at, reviewer, is_rejected FROM account');
        $this->addSql('DROP TABLE account');
        $this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL COLLATE BINARY, lastname VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, street VARCHAR(255) NOT NULL COLLATE BINARY, housenumber VARCHAR(10) NOT NULL COLLATE BINARY, zipcode VARCHAR(255) NOT NULL COLLATE BINARY, city VARCHAR(255) NOT NULL COLLATE BINARY, phone VARCHAR(255) NOT NULL COLLATE BINARY, type VARCHAR(255) NOT NULL COLLATE BINARY, company VARCHAR(255) NOT NULL COLLATE BINARY, token VARCHAR(255) NOT NULL COLLATE BINARY, verified_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, reviewed_at DATETIME DEFAULT NULL, reviewer VARCHAR(255) DEFAULT NULL COLLATE BINARY, is_rejected BOOLEAN NOT NULL, score NUMERIC(2, 1) DEFAULT NULL)');
        $this->addSql('INSERT INTO account (id, firstname, lastname, email, street, housenumber, zipcode, city, phone, type, company, token, verified_at, created_at, reviewed_at, reviewer, is_rejected) SELECT id, firstname, lastname, email, street, housenumber, zipcode, city, phone, type, company, token, verified_at, created_at, reviewed_at, reviewer, is_rejected FROM __temp__account');
        $this->addSql('DROP TABLE __temp__account');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4E7927C74 ON account (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_7D3656A4E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__account AS SELECT id, firstname, lastname, email, street, housenumber, zipcode, city, phone, type, company, token, verified_at, created_at, reviewed_at, reviewer, is_rejected FROM account');
        $this->addSql('DROP TABLE account');
        $this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, housenumber VARCHAR(10) NOT NULL, zipcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, verified_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, reviewed_at DATETIME DEFAULT NULL, reviewer VARCHAR(255) DEFAULT NULL, is_rejected BOOLEAN DEFAULT \'false\' NOT NULL)');
        $this->addSql('INSERT INTO account (id, firstname, lastname, email, street, housenumber, zipcode, city, phone, type, company, token, verified_at, created_at, reviewed_at, reviewer, is_rejected) SELECT id, firstname, lastname, email, street, housenumber, zipcode, city, phone, type, company, token, verified_at, created_at, reviewed_at, reviewer, is_rejected FROM __temp__account');
        $this->addSql('DROP TABLE __temp__account');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4E7927C74 ON account (email)');
    }
}

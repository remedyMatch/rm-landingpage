<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200410025527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_7D3656A4E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__account AS SELECT id, firstname, lastname, email, street, housenumber, zipcode, city, phone, type, company, token, verified_at, created_at, reviewed_at, reviewer, is_rejected, score FROM account');
        $this->addSql('DROP TABLE account');
        $this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL COLLATE BINARY, lastname VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, street VARCHAR(255) NOT NULL COLLATE BINARY, housenumber VARCHAR(10) NOT NULL COLLATE BINARY, zipcode VARCHAR(255) NOT NULL COLLATE BINARY, city VARCHAR(255) NOT NULL COLLATE BINARY, phone VARCHAR(255) NOT NULL COLLATE BINARY, verified_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, reviewed_at DATETIME DEFAULT NULL, reviewer VARCHAR(255) DEFAULT NULL COLLATE BINARY, is_rejected BOOLEAN NOT NULL, score NUMERIC(2, 1) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, token VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO account (id, firstname, lastname, email, street, housenumber, zipcode, city, phone, type, company, token, verified_at, created_at, reviewed_at, reviewer, is_rejected, score) SELECT id, firstname, lastname, email, street, housenumber, zipcode, city, phone, type, company, token, verified_at, created_at, reviewed_at, reviewer, is_rejected, score FROM __temp__account');
        $this->addSql('DROP TABLE __temp__account');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4E7927C74 ON account (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_7D3656A4E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__account AS SELECT id, firstname, lastname, email, street, housenumber, zipcode, city, phone, type, company, token, verified_at, created_at, reviewed_at, reviewer, is_rejected, score FROM account');
        $this->addSql('DROP TABLE account');
        $this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, housenumber VARCHAR(10) NOT NULL, zipcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, verified_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, reviewed_at DATETIME DEFAULT NULL, reviewer VARCHAR(255) DEFAULT NULL, is_rejected BOOLEAN NOT NULL, score NUMERIC(2, 1) DEFAULT NULL, type VARCHAR(255) NOT NULL COLLATE BINARY, company VARCHAR(255) NOT NULL COLLATE BINARY, token VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO account (id, firstname, lastname, email, street, housenumber, zipcode, city, phone, type, company, token, verified_at, created_at, reviewed_at, reviewer, is_rejected, score) SELECT id, firstname, lastname, email, street, housenumber, zipcode, city, phone, type, company, token, verified_at, created_at, reviewed_at, reviewer, is_rejected, score FROM __temp__account');
        $this->addSql('DROP TABLE __temp__account');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4E7927C74 ON account (email)');
    }
}

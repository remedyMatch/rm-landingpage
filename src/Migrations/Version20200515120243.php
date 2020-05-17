<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200515120243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE faq_entry (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', faq_section_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', question VARCHAR(255) NOT NULL, question_german VARCHAR(255) NOT NULL, answer LONGTEXT NOT NULL, answer_german LONGTEXT NOT NULL, INDEX IDX_8FF5477519C1D1F6 (faq_section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faq_section (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, title_german VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE faq_entry ADD CONSTRAINT FK_8FF5477519C1D1F6 FOREIGN KEY (faq_section_id) REFERENCES faq_section (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE faq_entry DROP FOREIGN KEY FK_8FF5477519C1D1F6');
        $this->addSql('DROP TABLE faq_entry');
        $this->addSql('DROP TABLE faq_section');
    }
}

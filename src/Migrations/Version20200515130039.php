<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;

final class Version20200515130039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `dynamic_term` (`id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT \'(DC2Type:uuid)\',`placeholder` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,`value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `UNIQ_B470FD698A90ABA9` (`placeholder`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE dynamic_term');
    }
}

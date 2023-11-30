<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130153939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photo CHANGE date_poste date_poste DATETIME NOT NULL, CHANGE likes_count likes_count INT NOT NULL, CHANGE dislikes_count dislikes_count INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photo CHANGE date_poste date_poste DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE likes_count likes_count INT DEFAULT NULL, CHANGE dislikes_count dislikes_count INT DEFAULT NULL');
    }
}

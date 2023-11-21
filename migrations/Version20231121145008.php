<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121145008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE likes_photo DROP FOREIGN KEY FK_A84B289EC51599E0');
        $this->addSql('DROP INDEX IDX_A84B289EC51599E0 ON likes_photo');
        $this->addSql('ALTER TABLE likes_photo CHANGE photo_id_id photo_id INT NOT NULL');
        $this->addSql('ALTER TABLE likes_photo ADD CONSTRAINT FK_A84B289E7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id)');
        $this->addSql('CREATE INDEX IDX_A84B289E7E9E4C8C ON likes_photo (photo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE likes_photo DROP FOREIGN KEY FK_A84B289E7E9E4C8C');
        $this->addSql('DROP INDEX IDX_A84B289E7E9E4C8C ON likes_photo');
        $this->addSql('ALTER TABLE likes_photo CHANGE photo_id photo_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE likes_photo ADD CONSTRAINT FK_A84B289EC51599E0 FOREIGN KEY (photo_id_id) REFERENCES photo (id)');
        $this->addSql('CREATE INDEX IDX_A84B289EC51599E0 ON likes_photo (photo_id_id)');
    }
}

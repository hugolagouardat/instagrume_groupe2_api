<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121144724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE likes_photo ADD photo_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE likes_photo ADD CONSTRAINT FK_A84B289EC51599E0 FOREIGN KEY (photo_id_id) REFERENCES photo (id)');
        $this->addSql('CREATE INDEX IDX_A84B289EC51599E0 ON likes_photo (photo_id_id)');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418A76ED395');
        $this->addSql('DROP INDEX IDX_14B78418A76ED395 ON photo');
        $this->addSql('ALTER TABLE photo DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE likes_photo DROP FOREIGN KEY FK_A84B289EC51599E0');
        $this->addSql('DROP INDEX IDX_A84B289EC51599E0 ON likes_photo');
        $this->addSql('ALTER TABLE likes_photo DROP photo_id_id');
        $this->addSql('ALTER TABLE photo ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_14B78418A76ED395 ON photo (user_id)');
    }
}

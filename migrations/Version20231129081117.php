<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231129081117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire ADD user_id INT DEFAULT NULL, ADD photo_id INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id)');
        $this->addSql('CREATE INDEX IDX_67F068BCA76ED395 ON commentaire (user_id)');
        $this->addSql('CREATE INDEX IDX_67F068BC7E9E4C8C ON commentaire (photo_id)');
        $this->addSql('ALTER TABLE likes_commentaire ADD commentaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE likes_commentaire ADD CONSTRAINT FK_1A3A4385BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
        $this->addSql('CREATE INDEX IDX_1A3A4385BA9CD190 ON likes_commentaire (commentaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC7E9E4C8C');
        $this->addSql('DROP INDEX IDX_67F068BCA76ED395 ON commentaire');
        $this->addSql('DROP INDEX IDX_67F068BC7E9E4C8C ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP user_id, DROP photo_id');
        $this->addSql('ALTER TABLE likes_commentaire DROP FOREIGN KEY FK_1A3A4385BA9CD190');
        $this->addSql('DROP INDEX IDX_1A3A4385BA9CD190 ON likes_commentaire');
        $this->addSql('ALTER TABLE likes_commentaire DROP commentaire_id');
    }
}

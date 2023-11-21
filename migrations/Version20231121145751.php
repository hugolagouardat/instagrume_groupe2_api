<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121145751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE likes_commentaire ADD user_id INT NOT NULL, ADD commentaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE likes_commentaire ADD CONSTRAINT FK_1A3A4385A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE likes_commentaire ADD CONSTRAINT FK_1A3A4385BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
        $this->addSql('CREATE INDEX IDX_1A3A4385A76ED395 ON likes_commentaire (user_id)');
        $this->addSql('CREATE INDEX IDX_1A3A4385BA9CD190 ON likes_commentaire (commentaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE likes_commentaire DROP FOREIGN KEY FK_1A3A4385A76ED395');
        $this->addSql('ALTER TABLE likes_commentaire DROP FOREIGN KEY FK_1A3A4385BA9CD190');
        $this->addSql('DROP INDEX IDX_1A3A4385A76ED395 ON likes_commentaire');
        $this->addSql('DROP INDEX IDX_1A3A4385BA9CD190 ON likes_commentaire');
        $this->addSql('ALTER TABLE likes_commentaire DROP user_id, DROP commentaire_id');
    }
}

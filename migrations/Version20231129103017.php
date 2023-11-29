<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231129103017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC7E9E4C8C');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC556CA8E7');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC556CA8E7 FOREIGN KEY (parentCommentId) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likes_commentaire DROP FOREIGN KEY FK_1A3A4385BA9CD190');
        $this->addSql('ALTER TABLE likes_commentaire ADD CONSTRAINT FK_1A3A4385BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likes_photo DROP FOREIGN KEY FK_A84B289EA76ED395');
        $this->addSql('ALTER TABLE likes_photo ADD CONSTRAINT FK_A84B289EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418A76ED395');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC556CA8E7');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC7E9E4C8C');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC556CA8E7 FOREIGN KEY (parentCommentId) REFERENCES commentaire (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id)');
        $this->addSql('ALTER TABLE likes_commentaire DROP FOREIGN KEY FK_1A3A4385BA9CD190');
        $this->addSql('ALTER TABLE likes_commentaire ADD CONSTRAINT FK_1A3A4385BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
        $this->addSql('ALTER TABLE likes_photo DROP FOREIGN KEY FK_A84B289EA76ED395');
        $this->addSql('ALTER TABLE likes_photo ADD CONSTRAINT FK_A84B289EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418A76ED395');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231212133735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC8A2F853C');
        $this->addSql('DROP INDEX IDX_67F068BC8A2F853C ON commentaire');
        $this->addSql('ALTER TABLE commentaire CHANGE commentaire_parint_id commentaire_parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCFDED4547 FOREIGN KEY (commentaire_parent_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_67F068BCFDED4547 ON commentaire (commentaire_parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCFDED4547');
        $this->addSql('DROP INDEX IDX_67F068BCFDED4547 ON commentaire');
        $this->addSql('ALTER TABLE commentaire CHANGE commentaire_parent_id commentaire_parint_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC8A2F853C FOREIGN KEY (commentaire_parint_id) REFERENCES commentaire (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_67F068BC8A2F853C ON commentaire (commentaire_parint_id)');
    }
}

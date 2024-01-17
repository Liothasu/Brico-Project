<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240116180056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007F8697D13');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007F8697D13');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007F8697D13 FOREIGN KEY (comment_id) REFERENCES dispute (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}

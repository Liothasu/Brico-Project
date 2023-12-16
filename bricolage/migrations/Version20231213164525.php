<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231213164525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog ADD slug VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE category ADD slug VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE product ADD slug VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE project ADD slug VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE supplier ADD slug VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE type ADD slug VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP slug');
        $this->addSql('ALTER TABLE product DROP slug');
        $this->addSql('ALTER TABLE category DROP slug');
        $this->addSql('ALTER TABLE type DROP slug');
        $this->addSql('ALTER TABLE supplier DROP slug');
        $this->addSql('ALTER TABLE project DROP slug');
    }
}

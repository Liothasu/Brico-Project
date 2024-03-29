<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329160656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE last_name last_name VARCHAR(100) NOT NULL, CHANGE first_name first_name VARCHAR(100) NOT NULL, CHANGE email email VARCHAR(100) NOT NULL, CHANGE num_street num_street VARCHAR(255) NOT NULL, CHANGE city city VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` CHANGE last_name last_name VARCHAR(50) NOT NULL, CHANGE first_name first_name VARCHAR(50) NOT NULL, CHANGE email email VARCHAR(50) NOT NULL, CHANGE num_street num_street VARCHAR(50) NOT NULL, CHANGE city city VARCHAR(50) NOT NULL');
    }
}

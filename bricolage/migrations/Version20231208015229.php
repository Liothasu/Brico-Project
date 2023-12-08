<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231208015229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, type_id INT DEFAULT NULL, title VARCHAR(50) NOT NULL, content VARCHAR(50) NOT NULL, INDEX IDX_C0155143A76ED395 (user_id), INDEX IDX_C0155143C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, blog_id INT DEFAULT NULL, user_id INT DEFAULT NULL, content VARCHAR(50) NOT NULL, time_com DATE NOT NULL, INDEX IDX_9474526CDAE07E97 (blog_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dispute (id INT AUTO_INCREMENT NOT NULL, order_command_id INT DEFAULT NULL, user_id INT DEFAULT NULL, project_id INT DEFAULT NULL, blog_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, title VARCHAR(50) NOT NULL, description VARCHAR(50) NOT NULL, INDEX IDX_3C925007803B95CF (order_command_id), INDEX IDX_3C925007A76ED395 (user_id), INDEX IDX_3C925007166D1F9C (project_id), INDEX IDX_3C925007DAE07E97 (blog_id), INDEX IDX_3C925007F8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE line_order (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, order_command_id INT DEFAULT NULL, selling_price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, INDEX IDX_AADB41B4584665A (product_id), INDEX IDX_AADB41B803B95CF (order_command_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(50) NOT NULL, time_msg DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_user (message_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_24064D90537A1329 (message_id), INDEX IDX_24064D90A76ED395 (user_id), PRIMARY KEY(message_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, statut_orders JSON NOT NULL, date_order DATE NOT NULL, payment_mod VARCHAR(255) NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, supplier_id INT DEFAULT NULL, reference VARCHAR(50) NOT NULL, name_product VARCHAR(50) NOT NULL, color VARCHAR(50) NOT NULL, designation VARCHAR(50) NOT NULL, quantity INT NOT NULL, unit_price INT NOT NULL, price_vat INT NOT NULL, vat INT NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), INDEX IDX_D34A04AD2ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name_project VARCHAR(50) NOT NULL, title VARCHAR(50) NOT NULL, description VARCHAR(50) NOT NULL, INDEX IDX_2FB3D0EEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo (id INT AUTO_INCREMENT NOT NULL, percent DOUBLE PRECISION NOT NULL, date_begin DATE NOT NULL, date_end DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo_product (promo_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_DB3EBC9FD0C07AFF (promo_id), INDEX IDX_DB3EBC9F4584665A (product_id), PRIMARY KEY(promo_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier (id INT AUTO_INCREMENT NOT NULL, name_factory VARCHAR(50) NOT NULL, phone_number VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, num_street VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL, postal_code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, roles JSON NOT NULL, username VARCHAR(180) NOT NULL, last_name VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, phone_number INT NOT NULL, num_street VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL, zip_code INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007803B95CF FOREIGN KEY (order_command_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007F8697D13 FOREIGN KEY (comment_id) REFERENCES dispute (id)');
        $this->addSql('ALTER TABLE line_order ADD CONSTRAINT FK_AADB41B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE line_order ADD CONSTRAINT FK_AADB41B803B95CF FOREIGN KEY (order_command_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE message_user ADD CONSTRAINT FK_24064D90537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_user ADD CONSTRAINT FK_24064D90A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE promo_product ADD CONSTRAINT FK_DB3EBC9FD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo_product ADD CONSTRAINT FK_DB3EBC9F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143A76ED395');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143C54C8C93');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CDAE07E97');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007803B95CF');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007A76ED395');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007166D1F9C');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007DAE07E97');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007F8697D13');
        $this->addSql('ALTER TABLE line_order DROP FOREIGN KEY FK_AADB41B4584665A');
        $this->addSql('ALTER TABLE line_order DROP FOREIGN KEY FK_AADB41B803B95CF');
        $this->addSql('ALTER TABLE message_user DROP FOREIGN KEY FK_24064D90537A1329');
        $this->addSql('ALTER TABLE message_user DROP FOREIGN KEY FK_24064D90A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD2ADD6D8C');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('ALTER TABLE promo_product DROP FOREIGN KEY FK_DB3EBC9FD0C07AFF');
        $this->addSql('ALTER TABLE promo_product DROP FOREIGN KEY FK_DB3EBC9F4584665A');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE dispute');
        $this->addSql('DROP TABLE line_order');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE message_user');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE promo');
        $this->addSql('DROP TABLE promo_product');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE `user`');
    }
}

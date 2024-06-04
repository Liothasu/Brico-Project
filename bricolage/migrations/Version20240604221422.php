<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240604221422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, featured_media_id INT DEFAULT NULL, author_id INT NOT NULL, title VARCHAR(50) NOT NULL, featured_text LONGTEXT NOT NULL, content JSON NOT NULL, slug VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C0155143E2532148 (featured_media_id), INDEX IDX_C0155143F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blog_type (blog_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_6C0EDBB9DAE07E97 (blog_id), INDEX IDX_6C0EDBB9C54C8C93 (type_id), PRIMARY KEY(blog_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, blog_id INT NOT NULL, user_id INT NOT NULL, parent_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9474526CDAE07E97 (blog_id), INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, value LONGTEXT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D48A2F7C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dispute (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, user_id INT DEFAULT NULL, project_id INT DEFAULT NULL, blog_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, title VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, problem_type VARCHAR(50) NOT NULL, INDEX IDX_3C9250078D9F6D38 (order_id), INDEX IDX_3C925007A76ED395 (user_id), INDEX IDX_3C925007166D1F9C (project_id), INDEX IDX_3C925007DAE07E97 (blog_id), INDEX IDX_3C925007F8697D13 (comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, name VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, alt_text VARCHAR(255) DEFAULT NULL, INDEX IDX_C53D045F4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE line_order (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, order_id INT NOT NULL, selling_price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, reservation_expire_at DATETIME NOT NULL, INDEX IDX_AADB41B4584665A (product_id), INDEX IDX_AADB41B8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, alt_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, recipient_id INT NOT NULL, original_message_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, time_msg DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_read TINYINT(1) NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FE92F8F78 (recipient_id), INDEX IDX_B6BD307F3ECD64BD (original_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, reference VARCHAR(20) NOT NULL, statut_orders JSON NOT NULL, date_order DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', payment_mode VARCHAR(255) NOT NULL, total DOUBLE PRECISION DEFAULT \'0\' NOT NULL, stripe_session_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F5299398AEA34913 (reference), INDEX IDX_F5299398A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, supplier_id INT DEFAULT NULL, reference VARCHAR(50) NOT NULL, name_product VARCHAR(50) NOT NULL, color VARCHAR(50) NOT NULL, designation VARCHAR(50) NOT NULL, stock INT NOT NULL, unit_price NUMERIC(12, 2) NOT NULL, price_vat NUMERIC(12, 2) NOT NULL, slug VARCHAR(50) NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), INDEX IDX_D34A04AD2ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name_project VARCHAR(50) NOT NULL, title VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_2FB3D0EEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, percent DOUBLE PRECISION NOT NULL, date_begin DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_end DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo_product (promo_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_DB3EBC9FD0C07AFF (promo_id), INDEX IDX_DB3EBC9F4584665A (product_id), PRIMARY KEY(promo_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier (id INT AUTO_INCREMENT NOT NULL, name_factory VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, color VARCHAR(255) NOT NULL, slug VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, roles JSON NOT NULL, username VARCHAR(180) NOT NULL, last_name VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, phone_number INT NOT NULL, num_street VARCHAR(255) NOT NULL, city VARCHAR(100) NOT NULL, zip_code INT NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143E2532148 FOREIGN KEY (featured_media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE blog_type ADD CONSTRAINT FK_6C0EDBB9DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_type ADD CONSTRAINT FK_6C0EDBB9C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C727ACA70 FOREIGN KEY (parent_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C9250078D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE line_order ADD CONSTRAINT FK_AADB41B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE line_order ADD CONSTRAINT FK_AADB41B8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE92F8F78 FOREIGN KEY (recipient_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F3ECD64BD FOREIGN KEY (original_message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE promo_product ADD CONSTRAINT FK_DB3EBC9FD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo_product ADD CONSTRAINT FK_DB3EBC9F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143E2532148');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143F675F31B');
        $this->addSql('ALTER TABLE blog_type DROP FOREIGN KEY FK_6C0EDBB9DAE07E97');
        $this->addSql('ALTER TABLE blog_type DROP FOREIGN KEY FK_6C0EDBB9C54C8C93');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CDAE07E97');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C727ACA70');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C9250078D9F6D38');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007A76ED395');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007166D1F9C');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007DAE07E97');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007F8697D13');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F4584665A');
        $this->addSql('ALTER TABLE line_order DROP FOREIGN KEY FK_AADB41B4584665A');
        $this->addSql('ALTER TABLE line_order DROP FOREIGN KEY FK_AADB41B8D9F6D38');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE92F8F78');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F3ECD64BD');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD2ADD6D8C');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('ALTER TABLE promo_product DROP FOREIGN KEY FK_DB3EBC9FD0C07AFF');
        $this->addSql('ALTER TABLE promo_product DROP FOREIGN KEY FK_DB3EBC9F4584665A');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE blog_type');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE dispute');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE line_order');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE promo');
        $this->addSql('DROP TABLE promo_product');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE `user`');
    }
}

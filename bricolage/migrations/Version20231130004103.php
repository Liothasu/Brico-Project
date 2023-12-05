<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130004103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message_user (message_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_24064D90537A1329 (message_id), INDEX IDX_24064D90A76ED395 (user_id), PRIMARY KEY(message_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo_product (promo_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_DB3EBC9FD0C07AFF (promo_id), INDEX IDX_DB3EBC9F4584665A (product_id), PRIMARY KEY(promo_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message_user ADD CONSTRAINT FK_24064D90537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_user ADD CONSTRAINT FK_24064D90A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo_product ADD CONSTRAINT FK_DB3EBC9FD0C07AFF FOREIGN KEY (promo_id) REFERENCES promo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promo_product ADD CONSTRAINT FK_DB3EBC9F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog ADD user_id INT DEFAULT NULL, ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blog ADD CONSTRAINT FK_C0155143C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_C0155143A76ED395 ON blog (user_id)');
        $this->addSql('CREATE INDEX IDX_C0155143C54C8C93 ON blog (type_id)');
        $this->addSql('ALTER TABLE comment ADD blog_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CDAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526CDAE07E97 ON comment (blog_id)');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('ALTER TABLE dispute ADD order_command_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD project_id INT DEFAULT NULL, ADD blog_id INT DEFAULT NULL, ADD comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007803B95CF FOREIGN KEY (order_command_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('ALTER TABLE dispute ADD CONSTRAINT FK_3C925007F8697D13 FOREIGN KEY (comment_id) REFERENCES dispute (id)');
        $this->addSql('CREATE INDEX IDX_3C925007803B95CF ON dispute (order_command_id)');
        $this->addSql('CREATE INDEX IDX_3C925007A76ED395 ON dispute (user_id)');
        $this->addSql('CREATE INDEX IDX_3C925007166D1F9C ON dispute (project_id)');
        $this->addSql('CREATE INDEX IDX_3C925007DAE07E97 ON dispute (blog_id)');
        $this->addSql('CREATE INDEX IDX_3C925007F8697D13 ON dispute (comment_id)');
        $this->addSql('ALTER TABLE line_order ADD product_id INT DEFAULT NULL, ADD order_command_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE line_order ADD CONSTRAINT FK_AADB41B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE line_order ADD CONSTRAINT FK_AADB41B803B95CF FOREIGN KEY (order_command_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_AADB41B4584665A ON line_order (product_id)');
        $this->addSql('CREATE INDEX IDX_AADB41B803B95CF ON line_order (order_command_id)');
        $this->addSql('ALTER TABLE `order` ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F5299398A76ED395 ON `order` (user_id)');
        $this->addSql('ALTER TABLE product ADD category_id INT DEFAULT NULL, ADD supplier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD2ADD6D8C ON product (supplier_id)');
        $this->addSql('ALTER TABLE project ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EEA76ED395 ON project (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message_user DROP FOREIGN KEY FK_24064D90537A1329');
        $this->addSql('ALTER TABLE message_user DROP FOREIGN KEY FK_24064D90A76ED395');
        $this->addSql('ALTER TABLE promo_product DROP FOREIGN KEY FK_DB3EBC9FD0C07AFF');
        $this->addSql('ALTER TABLE promo_product DROP FOREIGN KEY FK_DB3EBC9F4584665A');
        $this->addSql('DROP TABLE message_user');
        $this->addSql('DROP TABLE promo_product');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143A76ED395');
        $this->addSql('ALTER TABLE blog DROP FOREIGN KEY FK_C0155143C54C8C93');
        $this->addSql('DROP INDEX IDX_C0155143A76ED395 ON blog');
        $this->addSql('DROP INDEX IDX_C0155143C54C8C93 ON blog');
        $this->addSql('ALTER TABLE blog DROP user_id, DROP type_id');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CDAE07E97');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('DROP INDEX IDX_9474526CDAE07E97 ON comment');
        $this->addSql('DROP INDEX IDX_9474526CA76ED395 ON comment');
        $this->addSql('ALTER TABLE comment DROP blog_id, DROP user_id');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007803B95CF');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007A76ED395');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007166D1F9C');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007DAE07E97');
        $this->addSql('ALTER TABLE dispute DROP FOREIGN KEY FK_3C925007F8697D13');
        $this->addSql('DROP INDEX IDX_3C925007803B95CF ON dispute');
        $this->addSql('DROP INDEX IDX_3C925007A76ED395 ON dispute');
        $this->addSql('DROP INDEX IDX_3C925007166D1F9C ON dispute');
        $this->addSql('DROP INDEX IDX_3C925007DAE07E97 ON dispute');
        $this->addSql('DROP INDEX IDX_3C925007F8697D13 ON dispute');
        $this->addSql('ALTER TABLE dispute DROP order_command_id, DROP user_id, DROP project_id, DROP blog_id, DROP comment_id');
        $this->addSql('ALTER TABLE line_order DROP FOREIGN KEY FK_AADB41B4584665A');
        $this->addSql('ALTER TABLE line_order DROP FOREIGN KEY FK_AADB41B803B95CF');
        $this->addSql('DROP INDEX IDX_AADB41B4584665A ON line_order');
        $this->addSql('DROP INDEX IDX_AADB41B803B95CF ON line_order');
        $this->addSql('ALTER TABLE line_order DROP product_id, DROP order_command_id');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('DROP INDEX IDX_F5299398A76ED395 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP user_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD2ADD6D8C');
        $this->addSql('DROP INDEX IDX_D34A04AD12469DE2 ON product');
        $this->addSql('DROP INDEX IDX_D34A04AD2ADD6D8C ON product');
        $this->addSql('ALTER TABLE product DROP category_id, DROP supplier_id');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('DROP INDEX IDX_2FB3D0EEA76ED395 ON project');
        $this->addSql('ALTER TABLE project DROP user_id');
    }
}

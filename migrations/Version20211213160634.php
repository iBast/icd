<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211213160634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_product (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, price INT DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, picture_path VARCHAR(255) DEFAULT NULL, INDEX IDX_D079448712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_product_variant (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, stock INT DEFAULT NULL, INDEX IDX_C969A0294584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D079448712469DE2 FOREIGN KEY (category_id) REFERENCES shop_category (id)');
        $this->addSql('ALTER TABLE shop_product_variant ADD CONSTRAINT FK_C969A0294584665A FOREIGN KEY (product_id) REFERENCES shop_product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_product DROP FOREIGN KEY FK_D079448712469DE2');
        $this->addSql('ALTER TABLE shop_product_variant DROP FOREIGN KEY FK_C969A0294584665A');
        $this->addSql('DROP TABLE shop_category');
        $this->addSql('DROP TABLE shop_product');
        $this->addSql('DROP TABLE shop_product_variant');
    }
}

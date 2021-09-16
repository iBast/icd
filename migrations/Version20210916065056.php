<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210916065056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', to_name VARCHAR(255) DEFAULT NULL, to_adress VARCHAR(255) DEFAULT NULL, to_post_code VARCHAR(255) DEFAULT NULL, to_city VARCHAR(255) DEFAULT NULL, total_amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice_line (id INT AUTO_INCREMENT NOT NULL, invoice_id INT NOT NULL, date DATE NOT NULL, wording VARCHAR(255) NOT NULL, unit_price INT NOT NULL, qty INT NOT NULL, INDEX IDX_D3D1D6932989F1FD (invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invoice_line ADD CONSTRAINT FK_D3D1D6932989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_line DROP FOREIGN KEY FK_D3D1D6932989F1FD');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_line');
    }
}

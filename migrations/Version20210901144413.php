<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210901144413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE accounting (id INT AUTO_INCREMENT NOT NULL, document_id INT DEFAULT NULL, account_id INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', wording VARCHAR(255) DEFAULT NULL, debit INT DEFAULT NULL, credit INT DEFAULT NULL, UNIQUE INDEX UNIQ_6DC501E5C33F7837 (document_id), INDEX IDX_6DC501E59B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE accounting_document (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, total_amount INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accounting ADD CONSTRAINT FK_6DC501E5C33F7837 FOREIGN KEY (document_id) REFERENCES accounting_document (id)');
        $this->addSql('ALTER TABLE accounting ADD CONSTRAINT FK_6DC501E59B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE enrollment CHANGE season_id season_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accounting DROP FOREIGN KEY FK_6DC501E59B6B5FBA');
        $this->addSql('ALTER TABLE accounting DROP FOREIGN KEY FK_6DC501E5C33F7837');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE accounting');
        $this->addSql('DROP TABLE accounting_document');
        $this->addSql('ALTER TABLE enrollment CHANGE season_id season_id INT NOT NULL');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210902182023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE licence (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, cost INT NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enrollment ADD licence_id INT DEFAULT NULL, DROP has_leisure_licence, DROP has_competitive_licence, DROP has_young_licence');
        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E126EF07C9 FOREIGN KEY (licence_id) REFERENCES licence (id)');
        $this->addSql('CREATE INDEX IDX_DBDCD7E126EF07C9 ON enrollment (licence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enrollment DROP FOREIGN KEY FK_DBDCD7E126EF07C9');
        $this->addSql('DROP TABLE licence');
        $this->addSql('DROP INDEX IDX_DBDCD7E126EF07C9 ON enrollment');
        $this->addSql('ALTER TABLE enrollment ADD has_leisure_licence TINYINT(1) NOT NULL, ADD has_competitive_licence TINYINT(1) NOT NULL, ADD has_young_licence TINYINT(1) NOT NULL, DROP licence_id');
    }
}

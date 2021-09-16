<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915144234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accounting DROP FOREIGN KEY FK_6DC501E5C33F7837');
        $this->addSql('DROP INDEX UNIQ_6DC501E5C33F7837 ON accounting');
        $this->addSql('ALTER TABLE accounting DROP document_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accounting ADD document_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE accounting ADD CONSTRAINT FK_6DC501E5C33F7837 FOREIGN KEY (document_id) REFERENCES accounting_document (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DC501E5C33F7837 ON accounting (document_id)');
    }
}

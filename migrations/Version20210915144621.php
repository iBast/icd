<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915144621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accounting_document_accounting (accounting_document_id INT NOT NULL, accounting_id INT NOT NULL, INDEX IDX_99A0602FBF80C2E7 (accounting_document_id), INDEX IDX_99A0602F3B7DD068 (accounting_id), PRIMARY KEY(accounting_document_id, accounting_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accounting_document_accounting ADD CONSTRAINT FK_99A0602FBF80C2E7 FOREIGN KEY (accounting_document_id) REFERENCES accounting_document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE accounting_document_accounting ADD CONSTRAINT FK_99A0602F3B7DD068 FOREIGN KEY (accounting_id) REFERENCES accounting (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE accounting_document_accounting');
    }
}

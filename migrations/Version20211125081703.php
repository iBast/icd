<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211125081703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_comment (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content LONGTEXT NOT NULL, INDEX IDX_1123FBC371F7E88B (event_id), INDEX IDX_1123FBC3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE race (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date DATE DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, sign_in_link VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE race_member (race_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_1AB2E4AB6E59D40D (race_id), INDEX IDX_1AB2E4AB7597D3FE (member_id), PRIMARY KEY(race_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_comment ADD CONSTRAINT FK_1123FBC371F7E88B FOREIGN KEY (event_id) REFERENCES race (id)');
        $this->addSql('ALTER TABLE event_comment ADD CONSTRAINT FK_1123FBC3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE race_member ADD CONSTRAINT FK_1AB2E4AB6E59D40D FOREIGN KEY (race_id) REFERENCES race (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE race_member ADD CONSTRAINT FK_1AB2E4AB7597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_comment DROP FOREIGN KEY FK_1123FBC371F7E88B');
        $this->addSql('ALTER TABLE race_member DROP FOREIGN KEY FK_1AB2E4AB6E59D40D');
        $this->addSql('DROP TABLE event_comment');
        $this->addSql('DROP TABLE race');
        $this->addSql('DROP TABLE race_member');
    }
}

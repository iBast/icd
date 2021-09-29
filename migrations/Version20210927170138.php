<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210927170138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enrollment_young (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, season_id INT DEFAULT NULL, licence_id INT DEFAULT NULL, user_id INT DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, total_amount INT DEFAULT NULL, payment_method VARCHAR(255) DEFAULT NULL, payment_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ended_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_member TINYINT(1) NOT NULL, has_pool_acces TINYINT(1) NOT NULL, has_care_authorization TINYINT(1) NOT NULL, has_leave_alone_authorization TINYINT(1) NOT NULL, has_treatment TINYINT(1) NOT NULL, treatment_details LONGTEXT DEFAULT NULL, has_allergy TINYINT(1) NOT NULL, allergy_details LONGTEXT NOT NULL, emergency_contact VARCHAR(255) DEFAULT NULL, emergency_phone VARCHAR(255) DEFAULT NULL, medical_auth_path VARCHAR(255) DEFAULT NULL, fftri_doc_path VARCHAR(255) DEFAULT NULL, anti_doping_path VARCHAR(255) DEFAULT NULL, is_docs_valid TINYINT(1) NOT NULL, has_photo_authorization TINYINT(1) NOT NULL, INDEX IDX_AA9CD19D7E3C61F9 (owner_id), INDEX IDX_AA9CD19D4EC001D1 (season_id), INDEX IDX_AA9CD19D26EF07C9 (licence_id), INDEX IDX_AA9CD19DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enrollment_young ADD CONSTRAINT FK_AA9CD19D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE enrollment_young ADD CONSTRAINT FK_AA9CD19D4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE enrollment_young ADD CONSTRAINT FK_AA9CD19D26EF07C9 FOREIGN KEY (licence_id) REFERENCES licence (id)');
        $this->addSql('ALTER TABLE enrollment_young ADD CONSTRAINT FK_AA9CD19DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE enrollment_young');
    }
}

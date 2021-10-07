<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211007134831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enrollment DROP has_care_authorization, DROP has_leave_alone_authorization, DROP has_treatment, DROP treatment_details, DROP has_allergy, DROP allergy_details, DROP emergency_contact, DROP emergency_phone');
        $this->addSql('ALTER TABLE enrollment_young CHANGE fftri_doc2_path fftri_doc2_path VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enrollment ADD has_care_authorization TINYINT(1) NOT NULL, ADD has_leave_alone_authorization TINYINT(1) NOT NULL, ADD has_treatment TINYINT(1) NOT NULL, ADD treatment_details LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD has_allergy TINYINT(1) NOT NULL, ADD allergy_details LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD emergency_contact VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD emergency_phone VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE enrollment_young CHANGE fftri_doc2_path fftri_doc2_path VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

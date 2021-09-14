<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210801144610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enrollment (id INT AUTO_INCREMENT NOT NULL, season_id INT NOT NULL, member_id_id INT NOT NULL, status VARCHAR(255) DEFAULT NULL, total_amount INT DEFAULT NULL, payment_method VARCHAR(255) DEFAULT NULL, payment_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ended_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_member TINYINT(1) NOT NULL, has_leisure_licence TINYINT(1) NOT NULL, has_competitive_licence TINYINT(1) NOT NULL, has_young_licence TINYINT(1) NOT NULL, has_pool_acces TINYINT(1) NOT NULL, has_care_authorization TINYINT(1) NOT NULL, has_photo_authorization TINYINT(1) NOT NULL, has_leave_alone_authorization TINYINT(1) NOT NULL, has_treatment TINYINT(1) NOT NULL, treatment_details LONGTEXT DEFAULT NULL, has_allergy TINYINT(1) NOT NULL, allergy_details LONGTEXT DEFAULT NULL, emergency_contact VARCHAR(255) DEFAULT NULL, emergency_phone VARCHAR(255) DEFAULT NULL, medical_auth_path VARCHAR(255) DEFAULT NULL, fftri_doc_path VARCHAR(255) DEFAULT NULL, INDEX IDX_DBDCD7E14EC001D1 (season_id), INDEX IDX_DBDCD7E11D650BA4 (member_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `member` (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, adress VARCHAR(255) NOT NULL, post_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, mobile VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, birthday DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member_user (member_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_711BFA157597D3FE (member_id), INDEX IDX_711BFA15A76ED395 (user_id), PRIMARY KEY(member_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, year VARCHAR(255) NOT NULL, enrollment_status TINYINT(1) NOT NULL, membership_cost INT DEFAULT NULL, leisure_cost INT DEFAULT NULL, competitive_cost INT DEFAULT NULL, swim_cost INT DEFAULT NULL, young_cost INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E14EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E11D650BA4 FOREIGN KEY (member_id_id) REFERENCES `member` (id)');
        $this->addSql('ALTER TABLE member_user ADD CONSTRAINT FK_711BFA157597D3FE FOREIGN KEY (member_id) REFERENCES `member` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_user ADD CONSTRAINT FK_711BFA15A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enrollment DROP FOREIGN KEY FK_DBDCD7E11D650BA4');
        $this->addSql('ALTER TABLE member_user DROP FOREIGN KEY FK_711BFA157597D3FE');
        $this->addSql('ALTER TABLE enrollment DROP FOREIGN KEY FK_DBDCD7E14EC001D1');
        $this->addSql('DROP TABLE enrollment');
        $this->addSql('DROP TABLE `member`');
        $this->addSql('DROP TABLE member_user');
        $this->addSql('DROP TABLE season');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220208143203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sport (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE training (id INT AUTO_INCREMENT NOT NULL, sport_id INT NOT NULL, date DATE NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_D5128A8FAC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE training ADD CONSTRAINT FK_D5128A8FAC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE training DROP FOREIGN KEY FK_D5128A8FAC78BCF8');
        $this->addSql('DROP TABLE sport');
        $this->addSql('DROP TABLE training');
        $this->addSql('ALTER TABLE account CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE accounting CHANGE wording wording VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE accounting_document CHANGE path path VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type type VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE enrollment CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE payment_method payment_method VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE medical_auth_path medical_auth_path VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE fftri_doc_path fftri_doc_path VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE fftri_doc2_path fftri_doc2_path VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE enrollment_young CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE payment_method payment_method VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE treatment_details treatment_details LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE allergy_details allergy_details LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE emergency_contact emergency_contact VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE emergency_phone emergency_phone VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE medical_auth_path medical_auth_path VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE fftri_doc_path fftri_doc_path VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE anti_doping_path anti_doping_path VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE fftri_doc2_path fftri_doc2_path VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE event_comment CHANGE content content LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE invoice CHANGE to_name to_name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE to_adress to_adress VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE to_post_code to_post_code VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE to_city to_city VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE invoice_line CHANGE wording wording VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE licence CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `member` CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE adress adress VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE post_code post_code VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE mobile mobile VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE purchase CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE payment_method payment_method VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE purchase_item CHANGE product_name product_name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE variant_name variant_name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE race CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE location location VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE link link VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE sign_in_link sign_in_link VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE race_report CHANGE content content LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reset_password_request CHANGE selector selector VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE hashed_token hashed_token VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE season CHANGE year year VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE shop_category CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE shop_product CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE reference reference VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE picture_path picture_path VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE shop_product_variant CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `user` CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles JSON NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200722233009 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INT UNSIGNED AUTO_INCREMENT NOT NULL, flag_id INT UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_5373C966D17F50A6 (uuid), UNIQUE INDEX UNIQ_5373C966919FE4E5 (flag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE laboratory (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_FDC719A8D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_line_item (id INT UNSIGNED AUTO_INCREMENT NOT NULL, report_id INT UNSIGNED NOT NULL, adulterant_id INT UNSIGNED NOT NULL, value NUMERIC(5, 2) NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_BAE2BBDFD17F50A6 (uuid), INDEX IDX_BAE2BBDF4BD2A4C0 (report_id), INDEX IDX_BAE2BBDF65AFCB5F (adulterant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE yearly_report (id INT UNSIGNED AUTO_INCREMENT NOT NULL, country_id INT UNSIGNED NOT NULL, year INT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_483A80D9D17F50A6 (uuid), INDEX IDX_483A80D9F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE yearly_report_laboratory (yearly_report_id INT UNSIGNED NOT NULL, laboratory_id INT UNSIGNED NOT NULL, INDEX IDX_4DFCA5FB19968A3E (yearly_report_id), INDEX IDX_4DFCA5FB2F2A371E (laboratory_id), PRIMARY KEY(yearly_report_id, laboratory_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C966919FE4E5 FOREIGN KEY (flag_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE report_line_item ADD CONSTRAINT FK_BAE2BBDF4BD2A4C0 FOREIGN KEY (report_id) REFERENCES yearly_report (id)');
        $this->addSql('ALTER TABLE report_line_item ADD CONSTRAINT FK_BAE2BBDF65AFCB5F FOREIGN KEY (adulterant_id) REFERENCES adulterant (id)');
        $this->addSql('ALTER TABLE yearly_report ADD CONSTRAINT FK_483A80D9F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE yearly_report_laboratory ADD CONSTRAINT FK_4DFCA5FB19968A3E FOREIGN KEY (yearly_report_id) REFERENCES yearly_report (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE yearly_report_laboratory ADD CONSTRAINT FK_4DFCA5FB2F2A371E FOREIGN KEY (laboratory_id) REFERENCES laboratory (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE yearly_report DROP FOREIGN KEY FK_483A80D9F92F3E70');
        $this->addSql('ALTER TABLE yearly_report_laboratory DROP FOREIGN KEY FK_4DFCA5FB2F2A371E');
        $this->addSql('ALTER TABLE report_line_item DROP FOREIGN KEY FK_BAE2BBDF4BD2A4C0');
        $this->addSql('ALTER TABLE yearly_report_laboratory DROP FOREIGN KEY FK_4DFCA5FB19968A3E');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE laboratory');
        $this->addSql('DROP TABLE report_line_item');
        $this->addSql('DROP TABLE yearly_report');
        $this->addSql('DROP TABLE yearly_report_laboratory');
    }
}

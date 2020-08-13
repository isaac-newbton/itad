<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200813170220 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE excel_data_file (id INT AUTO_INCREMENT NOT NULL, file_id INT UNSIGNED NOT NULL, yearly_report_id INT UNSIGNED NOT NULL, nice_name VARCHAR(1000) DEFAULT NULL, UNIQUE INDEX UNIQ_BF9CC81293CB796C (file_id), INDEX IDX_BF9CC81219968A3E (yearly_report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE excel_data_file ADD CONSTRAINT FK_BF9CC81293CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE excel_data_file ADD CONSTRAINT FK_BF9CC81219968A3E FOREIGN KEY (yearly_report_id) REFERENCES yearly_report (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE excel_data_file');
    }
}

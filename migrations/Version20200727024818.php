<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200727024818 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file_download (id INT UNSIGNED AUTO_INCREMENT NOT NULL, file_id INT UNSIGNED NOT NULL, thumbnail_id INT UNSIGNED DEFAULT NULL, yearly_report_id INT UNSIGNED DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_C94A0DEDD17F50A6 (uuid), UNIQUE INDEX UNIQ_C94A0DED93CB796C (file_id), UNIQUE INDEX UNIQ_C94A0DEDFDFF2E92 (thumbnail_id), INDEX IDX_C94A0DED19968A3E (yearly_report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file_download ADD CONSTRAINT FK_C94A0DED93CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE file_download ADD CONSTRAINT FK_C94A0DEDFDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE file_download ADD CONSTRAINT FK_C94A0DED19968A3E FOREIGN KEY (yearly_report_id) REFERENCES yearly_report (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE file_download');
    }
}

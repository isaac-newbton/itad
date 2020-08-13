<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200813173522 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excel_data_file ADD uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF9CC812D17F50A6 ON excel_data_file (uuid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_BF9CC812D17F50A6 ON excel_data_file');
        $this->addSql('ALTER TABLE excel_data_file DROP uuid, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}

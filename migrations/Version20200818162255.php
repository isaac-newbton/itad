<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200818162255 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excel_data_file ADD user_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE excel_data_file ADD CONSTRAINT FK_BF9CC812A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BF9CC812A76ED395 ON excel_data_file (user_id)');
        $this->addSql('ALTER TABLE presentation ADD file_id INT UNSIGNED DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE presentation ADD CONSTRAINT FK_9B66E89393CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B66E89393CB796C ON presentation (file_id)');
        $this->addSql('ALTER TABLE publication ADD description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excel_data_file DROP FOREIGN KEY FK_BF9CC812A76ED395');
        $this->addSql('DROP INDEX IDX_BF9CC812A76ED395 ON excel_data_file');
        $this->addSql('ALTER TABLE excel_data_file DROP user_id');
        $this->addSql('ALTER TABLE presentation DROP FOREIGN KEY FK_9B66E89393CB796C');
        $this->addSql('DROP INDEX UNIQ_9B66E89393CB796C ON presentation');
        $this->addSql('ALTER TABLE presentation DROP file_id, DROP description');
        $this->addSql('ALTER TABLE publication DROP description');
    }
}

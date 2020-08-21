<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821155049 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication ADD file_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C677993CB796C FOREIGN KEY (file_id) REFERENCES media_file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AF3C677993CB796C ON publication (file_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C677993CB796C');
        $this->addSql('DROP INDEX UNIQ_AF3C677993CB796C ON publication');
        $this->addSql('ALTER TABLE publication DROP file_id');
    }
}

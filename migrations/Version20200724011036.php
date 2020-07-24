<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200724011036 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE presentation ADD uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B66E893D17F50A6 ON presentation (uuid)');
        $this->addSql('ALTER TABLE publication ADD uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AF3C6779D17F50A6 ON publication (uuid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_9B66E893D17F50A6 ON presentation');
        $this->addSql('ALTER TABLE presentation DROP uuid, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_AF3C6779D17F50A6 ON publication');
        $this->addSql('ALTER TABLE publication DROP uuid, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200716044454 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adulterant (id INT UNSIGNED AUTO_INCREMENT NOT NULL, thumbnail_id INT UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, synonyms LONGTEXT DEFAULT NULL, spanish_name VARCHAR(255) DEFAULT NULL, drug_class LONGTEXT DEFAULT NULL, occurance_usage LONGTEXT DEFAULT NULL, physiological_effect LONGTEXT DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_91D2E313D17F50A6 (uuid), UNIQUE INDEX UNIQ_91D2E313FDFF2E92 (thumbnail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, dt DATETIME NOT NULL, content LONGTEXT DEFAULT NULL, excerpt LONGTEXT DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_23A0E66D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_media_file (article_id INT UNSIGNED NOT NULL, media_file_id INT UNSIGNED NOT NULL, INDEX IDX_730138427294869C (article_id), INDEX IDX_73013842F21CFF25 (media_file_id), PRIMARY KEY(article_id, media_file_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_file (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, mime_type VARCHAR(255) DEFAULT NULL, size INT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_4FD8E9C3D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adulterant ADD CONSTRAINT FK_91D2E313FDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES media_file (id)');
        $this->addSql('ALTER TABLE article_media_file ADD CONSTRAINT FK_730138427294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_media_file ADD CONSTRAINT FK_73013842F21CFF25 FOREIGN KEY (media_file_id) REFERENCES media_file (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_media_file DROP FOREIGN KEY FK_730138427294869C');
        $this->addSql('ALTER TABLE adulterant DROP FOREIGN KEY FK_91D2E313FDFF2E92');
        $this->addSql('ALTER TABLE article_media_file DROP FOREIGN KEY FK_73013842F21CFF25');
        $this->addSql('DROP TABLE adulterant');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_media_file');
        $this->addSql('DROP TABLE media_file');
    }
}

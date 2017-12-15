<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171215102652 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE news_sources (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, INDEX name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news_items (id INT AUTO_INCREMENT NOT NULL, source INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, summary LONGTEXT DEFAULT NULL, url LONGTEXT DEFAULT NULL, publishedAt DATETIME DEFAULT NULL, createdAt DATETIME NOT NULL, INDEX IDX_9BA16E135F8A7F73 (source), INDEX date_idx (publishedAt), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE news_items ADD CONSTRAINT FK_9BA16E135F8A7F73 FOREIGN KEY (source) REFERENCES news_sources (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE news_items DROP FOREIGN KEY FK_9BA16E135F8A7F73');
        $this->addSql('DROP TABLE news_sources');
        $this->addSql('DROP TABLE news_items');
    }
}

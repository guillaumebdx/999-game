<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201220155051 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE block (id INT AUTO_INCREMENT NOT NULL, matrice_id INT DEFAULT NULL, x INT NOT NULL, y INT NOT NULL, number INT NOT NULL, INDEX IDX_831B9722BBF76425 (matrice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matrice (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, multiple INT NOT NULL, increment_new_block INT DEFAULT NULL, score INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_4DCF5DA0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE block ADD CONSTRAINT FK_831B9722BBF76425 FOREIGN KEY (matrice_id) REFERENCES matrice (id)');
        $this->addSql('ALTER TABLE matrice ADD CONSTRAINT FK_4DCF5DA0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE block DROP FOREIGN KEY FK_831B9722BBF76425');
        $this->addSql('ALTER TABLE matrice DROP FOREIGN KEY FK_4DCF5DA0A76ED395');
        $this->addSql('DROP TABLE block');
        $this->addSql('DROP TABLE matrice');
        $this->addSql('DROP TABLE user');
    }
}

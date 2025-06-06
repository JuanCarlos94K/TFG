<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250606053855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, volume_id INT DEFAULT NULL, number INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, cover VARCHAR(255) DEFAULT NULL, INDEX IDX_CBE5A3318FD80EEA (volume_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE chapter (id INT AUTO_INCREMENT NOT NULL, book_id INT DEFAULT NULL, role_id INT DEFAULT NULL, number INT NOT NULL, slug VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, published_at DATETIME DEFAULT NULL, INDEX IDX_F981B52E16A2B381 (book_id), INDEX IDX_F981B52ED60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(45) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE volume (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book ADD CONSTRAINT FK_CBE5A3318FD80EEA FOREIGN KEY (volume_id) REFERENCES volume (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter ADD CONSTRAINT FK_F981B52ED60322AC FOREIGN KEY (role_id) REFERENCES role (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE book DROP FOREIGN KEY FK_CBE5A3318FD80EEA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E16A2B381
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52ED60322AC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE book
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE chapter
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE volume
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}

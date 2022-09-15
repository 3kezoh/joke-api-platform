<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220915132421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE greeting_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE joke_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE rating_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, joke_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C30122C15 ON comment (joke_id)');
        $this->addSql('CREATE INDEX IDX_9474526CF8697D13 ON comment (comment_id)');
        $this->addSql('CREATE TABLE joke (id INT NOT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE joke_category (joke_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(joke_id, category_id))');
        $this->addSql('CREATE INDEX IDX_EBA09FC430122C15 ON joke_category (joke_id)');
        $this->addSql('CREATE INDEX IDX_EBA09FC412469DE2 ON joke_category (category_id)');
        $this->addSql('CREATE TABLE rating (id INT NOT NULL, joke_id INT NOT NULL, star INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D889262230122C15 ON rating (joke_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C30122C15 FOREIGN KEY (joke_id) REFERENCES joke (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joke_category ADD CONSTRAINT FK_EBA09FC430122C15 FOREIGN KEY (joke_id) REFERENCES joke (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE joke_category ADD CONSTRAINT FK_EBA09FC412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262230122C15 FOREIGN KEY (joke_id) REFERENCES joke (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE greeting');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE joke_category DROP CONSTRAINT FK_EBA09FC412469DE2');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CF8697D13');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C30122C15');
        $this->addSql('ALTER TABLE joke_category DROP CONSTRAINT FK_EBA09FC430122C15');
        $this->addSql('ALTER TABLE rating DROP CONSTRAINT FK_D889262230122C15');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE joke_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE rating_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE greeting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE greeting (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE joke');
        $this->addSql('DROP TABLE joke_category');
        $this->addSql('DROP TABLE rating');
    }
}

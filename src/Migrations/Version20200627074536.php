<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200627074536 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE list_text (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(1000) NOT NULL, status VARCHAR(12) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE todo_textcls (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_map ADD CONSTRAINT FK_96C824B1DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id)');
        $this->addSql('CREATE INDEX IDX_96C824B1DAE07E97 ON blog_map (blog_id)');
        $this->addSql('ALTER TABLE text CHANGE text text VARCHAR(1000) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE list_text');
        $this->addSql('DROP TABLE todo_textcls');
        $this->addSql('ALTER TABLE blog_map DROP FOREIGN KEY FK_96C824B1DAE07E97');
        $this->addSql('DROP INDEX IDX_96C824B1DAE07E97 ON blog_map');
        $this->addSql('ALTER TABLE text CHANGE text text VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

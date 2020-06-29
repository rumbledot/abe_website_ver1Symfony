<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200629070537 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE list_map DROP step');
        $this->addSql('ALTER TABLE list_text ADD listmap_id INT DEFAULT NULL, ADD step INT NOT NULL, CHANGE text text VARCHAR(50) NOT NULL, CHANGE status status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE list_text ADD CONSTRAINT FK_7E45782E6E27FB FOREIGN KEY (listmap_id) REFERENCES list_map (id)');
        $this->addSql('CREATE INDEX IDX_7E45782E6E27FB ON list_text (listmap_id)');
        $this->addSql('ALTER TABLE todo_text CHANGE status status TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE list_map ADD step INT NOT NULL');
        $this->addSql('ALTER TABLE list_text DROP FOREIGN KEY FK_7E45782E6E27FB');
        $this->addSql('DROP INDEX IDX_7E45782E6E27FB ON list_text');
        $this->addSql('ALTER TABLE list_text DROP listmap_id, DROP step, CHANGE text text VARCHAR(1000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE status status VARCHAR(12) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE todo_text CHANGE status status VARCHAR(12) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200630024645 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE list_text DROP FOREIGN KEY FK_7E45782E6E27FB');
        $this->addSql('DROP INDEX IDX_7E45782E6E27FB ON list_text');
        $this->addSql('ALTER TABLE list_text CHANGE listmap_id list_map_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE list_text ADD CONSTRAINT FK_7E45782ECA159D4A FOREIGN KEY (list_map_id) REFERENCES list_map (id)');
        $this->addSql('CREATE INDEX IDX_7E45782ECA159D4A ON list_text (list_map_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE list_text DROP FOREIGN KEY FK_7E45782ECA159D4A');
        $this->addSql('DROP INDEX IDX_7E45782ECA159D4A ON list_text');
        $this->addSql('ALTER TABLE list_text CHANGE list_map_id listmap_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE list_text ADD CONSTRAINT FK_7E45782E6E27FB FOREIGN KEY (listmap_id) REFERENCES list_map (id)');
        $this->addSql('CREATE INDEX IDX_7E45782E6E27FB ON list_text (listmap_id)');
    }
}

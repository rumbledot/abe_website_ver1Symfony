<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200610042418 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB4057E3C61F9');
        $this->addSql('DROP INDEX UNIQ_D95AB4057E3C61F9 ON user_profile');
        $this->addSql('ALTER TABLE user_profile DROP owner_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_profile ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB4057E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB4057E3C61F9 ON user_profile (owner_id)');
    }
}

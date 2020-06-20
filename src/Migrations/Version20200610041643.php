<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200610041643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB4059D86650F');
        $this->addSql('DROP INDEX UNIQ_D95AB4059D86650F ON user_profile');
        $this->addSql('ALTER TABLE user_profile CHANGE user_id_id owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB4057E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB4057E3C61F9 ON user_profile (owner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB4057E3C61F9');
        $this->addSql('DROP INDEX UNIQ_D95AB4057E3C61F9 ON user_profile');
        $this->addSql('ALTER TABLE user_profile CHANGE owner_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB4059D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D95AB4059D86650F ON user_profile (user_id_id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250420222309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE issue ADD status SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE project CHANGE key_code `key_code` VARCHAR(10) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE574985F4 ON project (`key_code`)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE issue DROP status');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE574985F4 ON project');
        $this->addSql('ALTER TABLE project CHANGE `key_code` key_code VARCHAR(5) NOT NULL');
    }
}

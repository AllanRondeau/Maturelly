<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202130339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hobby (id SERIAL NOT NULL, profile_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3964F337CCFA12B8 ON hobby (profile_id)');
        $this->addSql('CREATE TABLE profile_image (id SERIAL NOT NULL, profile_id INT NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_32E99B8DCCFA12B8 ON profile_image (profile_id)');
        $this->addSql('ALTER TABLE hobby ADD CONSTRAINT FK_3964F337CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile_image ADD CONSTRAINT FK_32E99B8DCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile ADD address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE profile DROP street_code');
        $this->addSql('ALTER TABLE profile DROP street');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE hobby DROP CONSTRAINT FK_3964F337CCFA12B8');
        $this->addSql('ALTER TABLE profile_image DROP CONSTRAINT FK_32E99B8DCCFA12B8');
        $this->addSql('DROP TABLE hobby');
        $this->addSql('DROP TABLE profile_image');
        $this->addSql('ALTER TABLE profile ADD street VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE profile RENAME COLUMN address TO street_code');
    }
}

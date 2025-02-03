<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250131135030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat (id UUID NOT NULL, user1_id UUID NOT NULL, user2_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_659DF2AA56AE248B ON chat (user1_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA441B8B65 ON chat (user2_id)');
        $this->addSql('COMMENT ON COLUMN chat.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN chat.user1_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN chat.user2_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE message (id UUID NOT NULL, chat_id UUID NOT NULL, sender_id UUID DEFAULT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307F1A9A7125 ON message (chat_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)');
        $this->addSql('COMMENT ON COLUMN message.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN message.chat_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN message.sender_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA56AE248B FOREIGN KEY (user1_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA441B8B65 FOREIGN KEY (user2_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE chat DROP CONSTRAINT FK_659DF2AA56AE248B');
        $this->addSql('ALTER TABLE chat DROP CONSTRAINT FK_659DF2AA441B8B65');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F1A9A7125');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FF624B39D');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE message');
    }
}

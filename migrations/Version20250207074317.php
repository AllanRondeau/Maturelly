<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207074317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIl ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE chat (id UUID NOT NULL, user1_id UUID NOT NULL, user2_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_659DF2AA56AE248B ON chat (user1_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA441B8B65 ON chat (user2_id)');
        $this->addSql('COMMENT ON COLUMN chat.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN chat.user1_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN chat.user2_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE hobby (id SERIAL NOT NULL, profile_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3964F337CCFA12B8 ON hobby (profile_id)');
        $this->addSql('CREATE TABLE "like" (id UUID NOT NULL, liker_id UUID NOT NULL, liked_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_like BOOLEAN DEFAULT true NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AC6340B3979F103A ON "like" (liker_id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3E2ED1879 ON "like" (liked_id)');
        $this->addSql('COMMENT ON COLUMN "like".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "like".liker_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "like".liked_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "like".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE message (id UUID NOT NULL, chat_id UUID NOT NULL, sender_id UUID DEFAULT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6BD307F1A9A7125 ON message (chat_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)');
        $this->addSql('COMMENT ON COLUMN message.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN message.chat_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN message.sender_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE notification (id UUID NOT NULL, user_id UUID NOT NULL, type VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, is_read BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)');
        $this->addSql('COMMENT ON COLUMN notification.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE profile (id SERIAL NOT NULL, user_id UUID NOT NULL, first_name VARCHAR(255) NOT NULL, family_name VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, birthday TIMESTAMP(0) WITH TIME ZONE NOT NULL, code VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8157AA0FA76ED395 ON profile (user_id)');
        $this->addSql('COMMENT ON COLUMN profile.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE profile_image (id SERIAL NOT NULL, profile_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_32E99B8DCCFA12B8 ON profile_image (profile_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA56AE248B FOREIGN KEY (user1_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA441B8B65 FOREIGN KEY (user2_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hobby ADD CONSTRAINT FK_3964F337CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3979F103A FOREIGN KEY (liker_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_AC6340B3E2ED1879 FOREIGN KEY (liked_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile_image ADD CONSTRAINT FK_32E99B8DCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE chat DROP CONSTRAINT FK_659DF2AA56AE248B');
        $this->addSql('ALTER TABLE chat DROP CONSTRAINT FK_659DF2AA441B8B65');
        $this->addSql('ALTER TABLE hobby DROP CONSTRAINT FK_3964F337CCFA12B8');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B3979F103A');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_AC6340B3E2ED1879');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F1A9A7125');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE profile DROP CONSTRAINT FK_8157AA0FA76ED395');
        $this->addSql('ALTER TABLE profile_image DROP CONSTRAINT FK_32E99B8DCCFA12B8');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE hobby');
        $this->addSql('DROP TABLE "like"');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE profile_image');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

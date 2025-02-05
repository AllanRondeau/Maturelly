<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250204153000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add like collections to User entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT IF EXISTS FK_like_liker');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT IF EXISTS FK_like_liked');
        $this->addSql('DROP INDEX IF EXISTS IDX_like_liker');
        $this->addSql('DROP INDEX IF EXISTS IDX_like_liked');
        $this->addSql('ALTER TABLE "like" ADD sent_likes_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE "like" ADD received_likes_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN "like".sent_likes_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "like".received_likes_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_like_sent_likes FOREIGN KEY (sent_likes_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "like" ADD CONSTRAINT FK_like_received_likes FOREIGN KEY (received_likes_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_like_sent_likes ON "like" (sent_likes_id)');
        $this->addSql('CREATE INDEX IDX_like_received_likes ON "like" (received_likes_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_like_sent_likes');
        $this->addSql('ALTER TABLE "like" DROP CONSTRAINT FK_like_received_likes');
        $this->addSql('DROP INDEX IDX_like_sent_likes');
        $this->addSql('DROP INDEX IDX_like_received_likes');
        $this->addSql('ALTER TABLE "like" DROP sent_likes_id');
        $this->addSql('ALTER TABLE "like" DROP received_likes_id');
    }
}
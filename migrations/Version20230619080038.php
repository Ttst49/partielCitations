<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619080038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quote_user (quote_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(quote_id, user_id))');
        $this->addSql('CREATE INDEX IDX_1F7489C3DB805178 ON quote_user (quote_id)');
        $this->addSql('CREATE INDEX IDX_1F7489C3A76ED395 ON quote_user (user_id)');
        $this->addSql('ALTER TABLE quote_user ADD CONSTRAINT FK_1F7489C3DB805178 FOREIGN KEY (quote_id) REFERENCES quote (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quote_user ADD CONSTRAINT FK_1F7489C3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE quote_user DROP CONSTRAINT FK_1F7489C3DB805178');
        $this->addSql('ALTER TABLE quote_user DROP CONSTRAINT FK_1F7489C3A76ED395');
        $this->addSql('DROP TABLE quote_user');
    }
}

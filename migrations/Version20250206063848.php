<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206063848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredients_logs (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, old_quantity INT NOT NULL, new_quantity INT NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ingredients_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C4F614CB3EC4DCE ON ingredients_logs (ingredients_id)');
        $this->addSql('ALTER TABLE ingredients_logs ADD CONSTRAINT FK_C4F614CB3EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredients_logs DROP CONSTRAINT FK_C4F614CB3EC4DCE');
        $this->addSql('DROP TABLE ingredients_logs');
    }
}

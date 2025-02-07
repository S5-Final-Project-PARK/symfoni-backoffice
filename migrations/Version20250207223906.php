<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207223906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD unit BIGINT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD unit_price BIGINT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD confirmation BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP unit');
        $this->addSql('ALTER TABLE orders DROP unit_price');
        $this->addSql('ALTER TABLE orders DROP email');
        $this->addSql('ALTER TABLE orders DROP confirmation');
    }
}

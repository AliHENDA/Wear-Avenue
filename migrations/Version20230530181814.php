<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230530181814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP carrier_name, DROP carrier_price, DROP delivery_address, DROP reference');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart ADD carrier_name VARCHAR(255) DEFAULT NULL, ADD carrier_price DOUBLE PRECISION DEFAULT NULL, ADD delivery_address VARCHAR(255) DEFAULT NULL, ADD reference VARCHAR(255) DEFAULT NULL');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230530211206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD order_details_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD8C0FA77 FOREIGN KEY (order_details_id) REFERENCES order_details (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD8C0FA77 ON product (order_details_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD8C0FA77');
        $this->addSql('DROP INDEX IDX_D34A04AD8C0FA77 ON product');
        $this->addSql('ALTER TABLE product DROP order_details_id');
    }
}

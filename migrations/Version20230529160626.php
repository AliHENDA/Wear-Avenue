<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230529160626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B74584665A');
        $this->addSql('DROP INDEX IDX_BA388B74584665A ON cart');
        $this->addSql('ALTER TABLE cart CHANGE product_id inventory_item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7536BF4A2 FOREIGN KEY (inventory_item_id) REFERENCES inventory (id)');
        $this->addSql('CREATE INDEX IDX_BA388B7536BF4A2 ON cart (inventory_item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7536BF4A2');
        $this->addSql('DROP INDEX IDX_BA388B7536BF4A2 ON cart');
        $this->addSql('ALTER TABLE cart CHANGE inventory_item_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B74584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_BA388B74584665A ON cart (product_id)');
    }
}

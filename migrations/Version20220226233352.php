<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220226233352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD provider_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A53A8AA FOREIGN KEY (provider_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_F5299398A53A8AA ON `order` (provider_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A53A8AA');
        $this->addSql('DROP INDEX IDX_F5299398A53A8AA ON `order`');
        $this->addSql('ALTER TABLE `order` DROP provider_id');
    }
}

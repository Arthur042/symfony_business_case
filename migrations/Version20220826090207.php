<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220826090207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contain DROP FOREIGN KEY FK_4BEFF7C86F5D8297');
        $this->addSql('ALTER TABLE contain ADD CONSTRAINT FK_4BEFF7C86F5D8297 FOREIGN KEY (bag_id) REFERENCES bag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contain DROP FOREIGN KEY FK_4BEFF7C86F5D8297');
        $this->addSql('ALTER TABLE contain ADD CONSTRAINT FK_4BEFF7C86F5D8297 FOREIGN KEY (bag_id) REFERENCES bag (id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223185622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activation_code ADD is_available TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE purchase_detail DROP INDEX UNIQ_F5697DC6558FBEB9, ADD INDEX IDX_F5697DC6558FBEB9 (purchase_id)');
        $this->addSql('ALTER TABLE purchase_detail DROP INDEX UNIQ_F5697DC6E48FD905, ADD INDEX IDX_F5697DC6E48FD905 (game_id)');
        $this->addSql('ALTER TABLE purchase_detail CHANGE activation_code_id activation_code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE support_ticket ADD purchase_detail_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE support_ticket ADD CONSTRAINT FK_1F5A4D535D9CF170 FOREIGN KEY (purchase_detail_id) REFERENCES purchase_detail (id)');
        $this->addSql('CREATE INDEX IDX_1F5A4D535D9CF170 ON support_ticket (purchase_detail_id)');
        $this->addSql('ALTER TABLE user ADD stripe_id VARCHAR(255) DEFAULT NULL, CHANGE lastname lastname VARCHAR(255) DEFAULT NULL, CHANGE firstname firstname VARCHAR(255) DEFAULT NULL, CHANGE last_connection last_connection DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activation_code DROP is_available');
        $this->addSql('ALTER TABLE purchase_detail DROP INDEX IDX_F5697DC6558FBEB9, ADD UNIQUE INDEX UNIQ_F5697DC6558FBEB9 (purchase_id)');
        $this->addSql('ALTER TABLE purchase_detail DROP INDEX IDX_F5697DC6E48FD905, ADD UNIQUE INDEX UNIQ_F5697DC6E48FD905 (game_id)');
        $this->addSql('ALTER TABLE purchase_detail CHANGE activation_code_id activation_code_id INT NOT NULL');
        $this->addSql('ALTER TABLE support_ticket DROP FOREIGN KEY FK_1F5A4D535D9CF170');
        $this->addSql('DROP INDEX IDX_1F5A4D535D9CF170 ON support_ticket');
        $this->addSql('ALTER TABLE support_ticket DROP purchase_detail_id');
        $this->addSql('ALTER TABLE user DROP stripe_id, CHANGE lastname lastname VARCHAR(255) NOT NULL, CHANGE firstname firstname VARCHAR(255) NOT NULL, CHANGE last_connection last_connection DATETIME NOT NULL');
    }
}

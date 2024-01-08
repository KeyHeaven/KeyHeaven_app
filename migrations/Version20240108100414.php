<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108100414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activation_code (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, distribution_date DATETIME NOT NULL, duration DATETIME NOT NULL, INDEX IDX_FA574C9AE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_detail (id INT AUTO_INCREMENT NOT NULL, purchase_id INT NOT NULL, game_id INT DEFAULT NULL, activation_code_id INT NOT NULL, quantity INT NOT NULL, unit_price DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_F5697DC6558FBEB9 (purchase_id), UNIQUE INDEX UNIQ_F5697DC6E48FD905 (game_id), UNIQUE INDEX UNIQ_F5697DC63F063CB2 (activation_code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activation_code ADD CONSTRAINT FK_FA574C9AE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE purchase_detail ADD CONSTRAINT FK_F5697DC6558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchasing (id)');
        $this->addSql('ALTER TABLE purchase_detail ADD CONSTRAINT FK_F5697DC6E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE purchase_detail ADD CONSTRAINT FK_F5697DC63F063CB2 FOREIGN KEY (activation_code_id) REFERENCES activation_code (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activation_code DROP FOREIGN KEY FK_FA574C9AE48FD905');
        $this->addSql('ALTER TABLE purchase_detail DROP FOREIGN KEY FK_F5697DC6558FBEB9');
        $this->addSql('ALTER TABLE purchase_detail DROP FOREIGN KEY FK_F5697DC6E48FD905');
        $this->addSql('ALTER TABLE purchase_detail DROP FOREIGN KEY FK_F5697DC63F063CB2');
        $this->addSql('DROP TABLE activation_code');
        $this->addSql('DROP TABLE purchase_detail');
    }
}

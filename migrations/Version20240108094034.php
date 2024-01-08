<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108094034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_information (id INT AUTO_INCREMENT NOT NULL, adress LONGTEXT NOT NULL, city VARCHAR(255) NOT NULL, department VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD user_information_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494575EE58 FOREIGN KEY (user_information_id) REFERENCES user_information (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6494575EE58 ON user (user_information_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494575EE58');
        $this->addSql('DROP TABLE user_information');
        $this->addSql('DROP INDEX IDX_8D93D6494575EE58 ON user');
        $this->addSql('ALTER TABLE user DROP user_information_id');
    }
}

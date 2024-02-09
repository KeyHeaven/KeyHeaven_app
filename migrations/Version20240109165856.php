<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109165856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494575EE58');
        $this->addSql('DROP INDEX IDX_8D93D6494575EE58 ON user');
        $this->addSql('ALTER TABLE user DROP user_information_id');
        $this->addSql('ALTER TABLE user_information ADD user_id INT DEFAULT NULL, CHANGE adress address LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user_information ADD CONSTRAINT FK_8062D116A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8062D116A76ED395 ON user_information (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_information DROP FOREIGN KEY FK_8062D116A76ED395');
        $this->addSql('DROP INDEX IDX_8062D116A76ED395 ON user_information');
        $this->addSql('ALTER TABLE user_information DROP user_id, CHANGE address adress LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE user ADD user_information_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494575EE58 FOREIGN KEY (user_information_id) REFERENCES user_information (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D6494575EE58 ON user (user_information_id)');
    }
}

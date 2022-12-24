<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221224221910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banco (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agencia ADD banco_id INT NOT NULL');
        $this->addSql('ALTER TABLE agencia ADD CONSTRAINT FK_EB6C2B99CC04A73E FOREIGN KEY (banco_id) REFERENCES banco (id)');
        $this->addSql('CREATE INDEX IDX_EB6C2B99CC04A73E ON agencia (banco_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agencia DROP FOREIGN KEY FK_EB6C2B99CC04A73E');
        $this->addSql('DROP TABLE banco');
        $this->addSql('DROP INDEX IDX_EB6C2B99CC04A73E ON agencia');
        $this->addSql('ALTER TABLE agencia DROP banco_id');
    }
}

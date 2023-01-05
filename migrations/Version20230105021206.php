<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230105021206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criando Entitys e seus relacionamentos';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conta (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, agencia_id INT DEFAULT NULL, tipo_conta_id INT DEFAULT NULL, numero VARCHAR(255) NOT NULL, saldo DOUBLE PRECISION NOT NULL, INDEX IDX_485A16C3A76ED395 (user_id), INDEX IDX_485A16C3A6F796BE (agencia_id), INDEX IDX_485A16C3B44BBA95 (tipo_conta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gerente (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nome VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_306C486DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_conta (id INT AUTO_INCREMENT NOT NULL, tipo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transacao (id INT AUTO_INCREMENT NOT NULL, trasacao_contas_id INT DEFAULT NULL, descricao VARCHAR(255) NOT NULL, valor VARCHAR(255) NOT NULL, data DATETIME NOT NULL, INDEX IDX_6C9E60CE6CB30353 (trasacao_contas_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conta ADD CONSTRAINT FK_485A16C3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE conta ADD CONSTRAINT FK_485A16C3A6F796BE FOREIGN KEY (agencia_id) REFERENCES agencia (id)');
        $this->addSql('ALTER TABLE conta ADD CONSTRAINT FK_485A16C3B44BBA95 FOREIGN KEY (tipo_conta_id) REFERENCES tipo_conta (id)');
        $this->addSql('ALTER TABLE gerente ADD CONSTRAINT FK_306C486DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transacao ADD CONSTRAINT FK_6C9E60CE6CB30353 FOREIGN KEY (trasacao_contas_id) REFERENCES conta (id)');
        $this->addSql('ALTER TABLE agencia ADD gerente_id INT DEFAULT NULL, CHANGE banco_id banco_id INT NOT NULL');
        $this->addSql('ALTER TABLE agencia ADD CONSTRAINT FK_EB6C2B995AEA750D FOREIGN KEY (gerente_id) REFERENCES gerente (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB6C2B995AEA750D ON agencia (gerente_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agencia DROP FOREIGN KEY FK_EB6C2B995AEA750D');
        $this->addSql('ALTER TABLE conta DROP FOREIGN KEY FK_485A16C3A76ED395');
        $this->addSql('ALTER TABLE conta DROP FOREIGN KEY FK_485A16C3A6F796BE');
        $this->addSql('ALTER TABLE conta DROP FOREIGN KEY FK_485A16C3B44BBA95');
        $this->addSql('ALTER TABLE gerente DROP FOREIGN KEY FK_306C486DA76ED395');
        $this->addSql('ALTER TABLE transacao DROP FOREIGN KEY FK_6C9E60CE6CB30353');
        $this->addSql('DROP TABLE conta');
        $this->addSql('DROP TABLE gerente');
        $this->addSql('DROP TABLE tipo_conta');
        $this->addSql('DROP TABLE transacao');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX UNIQ_EB6C2B995AEA750D ON agencia');
        $this->addSql('ALTER TABLE agencia DROP gerente_id, CHANGE banco_id banco_id INT DEFAULT 2');
    }
}

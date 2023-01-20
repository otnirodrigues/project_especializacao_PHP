<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230119212921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transacao ADD conta_destino_id INT DEFAULT NULL, ADD conta_remetente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transacao ADD CONSTRAINT FK_6C9E60CE88825F03 FOREIGN KEY (conta_destino_id) REFERENCES conta (id)');
        $this->addSql('ALTER TABLE transacao ADD CONSTRAINT FK_6C9E60CEF4255778 FOREIGN KEY (conta_remetente_id) REFERENCES conta (id)');
        $this->addSql('CREATE INDEX IDX_6C9E60CE88825F03 ON transacao (conta_destino_id)');
        $this->addSql('CREATE INDEX IDX_6C9E60CEF4255778 ON transacao (conta_remetente_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transacao DROP FOREIGN KEY FK_6C9E60CE88825F03');
        $this->addSql('ALTER TABLE transacao DROP FOREIGN KEY FK_6C9E60CEF4255778');
        $this->addSql('DROP INDEX IDX_6C9E60CE88825F03 ON transacao');
        $this->addSql('DROP INDEX IDX_6C9E60CEF4255778 ON transacao');
        $this->addSql('ALTER TABLE transacao DROP conta_destino_id, DROP conta_remetente_id');
    }
}

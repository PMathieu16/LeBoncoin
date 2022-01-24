<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220124154335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad DROP FOREIGN KEY FK_77E0ED581E27F6BF');
        $this->addSql('DROP INDEX IDX_77E0ED581E27F6BF ON ad');
        $this->addSql('ALTER TABLE ad DROP question_id');
        $this->addSql('ALTER TABLE question ADD ad_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E4F34D596 FOREIGN KEY (ad_id) REFERENCES ad (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E4F34D596 ON question (ad_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ad ADD question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ad ADD CONSTRAINT FK_77E0ED581E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_77E0ED581E27F6BF ON ad (question_id)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E4F34D596');
        $this->addSql('DROP INDEX IDX_B6F7494E4F34D596 ON question');
        $this->addSql('ALTER TABLE question DROP ad_id');
    }
}

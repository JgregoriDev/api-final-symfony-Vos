<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221128190441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comprar (id INT AUTO_INCREMENT NOT NULL, usuari_id INT DEFAULT NULL, productes LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', preu INT NOT NULL, data_compra DATE DEFAULT NULL, INDEX IDX_4195D1215F263030 (usuari_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comprar ADD CONSTRAINT FK_4195D1215F263030 FOREIGN KEY (usuari_id) REFERENCES usuari (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comprar DROP FOREIGN KEY FK_4195D1215F263030');
        $this->addSql('DROP TABLE comprar');
    }
}

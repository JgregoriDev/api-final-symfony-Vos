<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221112193713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genere (id INT AUTO_INCREMENT NOT NULL, genere VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genere_videojoc (genere_id INT NOT NULL, videojoc_id INT NOT NULL, INDEX IDX_D8600574D35A57F1 (genere_id), INDEX IDX_D8600574B606CCF2 (videojoc_id), PRIMARY KEY(genere_id, videojoc_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marca (id INT AUTO_INCREMENT NOT NULL, marca VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plataforma (id INT AUTO_INCREMENT NOT NULL, marca_id INT DEFAULT NULL, plataforma VARCHAR(255) NOT NULL, INDEX IDX_A0FE8A1E81EF0041 (marca_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuari (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, ban TINYINT(1) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_68CC94FFE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE videojoc (id INT AUTO_INCREMENT NOT NULL, titul VARCHAR(255) NOT NULL, descripcio VARCHAR(255) DEFAULT NULL, fecha_estreno DATE DEFAULT NULL, portada VARCHAR(255) DEFAULT NULL, cantitat INT NOT NULL, preu INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE videojoc_plataforma (videojoc_id INT NOT NULL, plataforma_id INT NOT NULL, INDEX IDX_F5063A72B606CCF2 (videojoc_id), INDEX IDX_F5063A72EB90E430 (plataforma_id), PRIMARY KEY(videojoc_id, plataforma_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE votacio (id INT AUTO_INCREMENT NOT NULL, usuari_votacio_id INT DEFAULT NULL, videojoc_id INT DEFAULT NULL, votacio SMALLINT NOT NULL, missatge VARCHAR(255) DEFAULT NULL, INDEX IDX_471D88D79216984B (usuari_votacio_id), INDEX IDX_471D88D7B606CCF2 (videojoc_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE genere_videojoc ADD CONSTRAINT FK_D8600574D35A57F1 FOREIGN KEY (genere_id) REFERENCES genere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genere_videojoc ADD CONSTRAINT FK_D8600574B606CCF2 FOREIGN KEY (videojoc_id) REFERENCES videojoc (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plataforma ADD CONSTRAINT FK_A0FE8A1E81EF0041 FOREIGN KEY (marca_id) REFERENCES marca (id)');
        $this->addSql('ALTER TABLE videojoc_plataforma ADD CONSTRAINT FK_F5063A72B606CCF2 FOREIGN KEY (videojoc_id) REFERENCES videojoc (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE videojoc_plataforma ADD CONSTRAINT FK_F5063A72EB90E430 FOREIGN KEY (plataforma_id) REFERENCES plataforma (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE votacio ADD CONSTRAINT FK_471D88D79216984B FOREIGN KEY (usuari_votacio_id) REFERENCES usuari (id)');
        $this->addSql('ALTER TABLE votacio ADD CONSTRAINT FK_471D88D7B606CCF2 FOREIGN KEY (videojoc_id) REFERENCES videojoc (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genere_videojoc DROP FOREIGN KEY FK_D8600574D35A57F1');
        $this->addSql('ALTER TABLE genere_videojoc DROP FOREIGN KEY FK_D8600574B606CCF2');
        $this->addSql('ALTER TABLE plataforma DROP FOREIGN KEY FK_A0FE8A1E81EF0041');
        $this->addSql('ALTER TABLE videojoc_plataforma DROP FOREIGN KEY FK_F5063A72B606CCF2');
        $this->addSql('ALTER TABLE videojoc_plataforma DROP FOREIGN KEY FK_F5063A72EB90E430');
        $this->addSql('ALTER TABLE votacio DROP FOREIGN KEY FK_471D88D79216984B');
        $this->addSql('ALTER TABLE votacio DROP FOREIGN KEY FK_471D88D7B606CCF2');
        $this->addSql('DROP TABLE genere');
        $this->addSql('DROP TABLE genere_videojoc');
        $this->addSql('DROP TABLE marca');
        $this->addSql('DROP TABLE plataforma');
        $this->addSql('DROP TABLE usuari');
        $this->addSql('DROP TABLE videojoc');
        $this->addSql('DROP TABLE videojoc_plataforma');
        $this->addSql('DROP TABLE votacio');
    }
}

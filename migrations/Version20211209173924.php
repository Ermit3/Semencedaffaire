<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211209173924 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX id ON categorie');
        $this->addSql('ALTER TABLE categorie CHANGE user_id user_id INT DEFAULT NULL, CHANGE afficher afficher TINYINT(1) NOT NULL, CHANGE upload_at upload_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_497DD634A76ED395 ON categorie (user_id)');
        $this->addSql('ALTER TABLE cotisations CHANGE utilisateur_id utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cotisations ADD CONSTRAINT FK_C51B351CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_C51B351CFB88E14F ON cotisations (utilisateur_id)');
        $this->addSql('ALTER TABLE couleur CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE degrade degrade VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX id ON presentation');
        $this->addSql('ALTER TABLE presentation CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE filename filename VARCHAR(255) DEFAULT NULL, CHANGE titre titre VARCHAR(100) DEFAULT NULL, CHANGE soustitre soustitre VARCHAR(255) DEFAULT NULL, CHANGE texte texte LONGTEXT DEFAULT NULL, CHANGE utilisateur_id utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE presentation ADD CONSTRAINT FK_9B66E893FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_9B66E893FB88E14F ON presentation (utilisateur_id)');
        $this->addSql('ALTER TABLE produit CHANGE prix prix NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)');
        $this->addSql('DROP INDEX id_2 ON sponsors');
        $this->addSql('DROP INDEX id ON sponsors');
        $this->addSql('ALTER TABLE sponsors ADD login VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD mail VARCHAR(255) NOT NULL, ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD montant DOUBLE PRECISION NOT NULL, ADD statut VARCHAR(100) NOT NULL, ADD source VARCHAR(255) NOT NULL, ADD afficher TINYINT(1) NOT NULL, ADD lft INT NOT NULL, ADD lvl INT NOT NULL, ADD rgt INT NOT NULL, ADD image VARCHAR(255) DEFAULT NULL, ADD upload_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE groupe_id groupe_id INT DEFAULT NULL, CHANGE lft lft INT NOT NULL, CHANGE lvl lvl INT NOT NULL, CHANGE rgt rgt INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3A977936C FOREIGN KEY (tree_root) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3727ACA70 FOREIGN KEY (parent_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_1D1C63B3A977936C ON utilisateur (tree_root)');
        $this->addSql('CREATE INDEX IDX_1D1C63B3727ACA70 ON utilisateur (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634A76ED395');
        $this->addSql('DROP INDEX IDX_497DD634A76ED395 ON categorie');
        $this->addSql('ALTER TABLE categorie CHANGE user_id user_id INT NOT NULL, CHANGE upload_at upload_at DATETIME NOT NULL, CHANGE afficher afficher INT NOT NULL');
        $this->addSql('CREATE INDEX id ON categorie (id)');
        $this->addSql('ALTER TABLE cotisations DROP FOREIGN KEY FK_C51B351CFB88E14F');
        $this->addSql('DROP INDEX IDX_C51B351CFB88E14F ON cotisations');
        $this->addSql('ALTER TABLE cotisations CHANGE utilisateur_id utilisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE couleur CHANGE nom nom VARCHAR(30) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE degrade degrade VARCHAR(30) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`');
        $this->addSql('ALTER TABLE presentation DROP FOREIGN KEY FK_9B66E893FB88E14F');
        $this->addSql('DROP INDEX IDX_9B66E893FB88E14F ON presentation');
        $this->addSql('ALTER TABLE presentation CHANGE id id INT NOT NULL, CHANGE utilisateur_id utilisateur_id INT NOT NULL, CHANGE filename filename VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE titre titre VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE soustitre soustitre VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, CHANGE texte texte TEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('CREATE INDEX id ON presentation (id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('DROP INDEX IDX_29A5EC27BCF5E72D ON produit');
        $this->addSql('ALTER TABLE produit CHANGE prix prix NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE sponsors DROP login, DROP password, DROP mail, DROP roles, DROP montant, DROP statut, DROP source, DROP afficher, DROP lft, DROP lvl, DROP rgt, DROP image, DROP upload_at');
        $this->addSql('CREATE UNIQUE INDEX id_2 ON sponsors (id)');
        $this->addSql('CREATE INDEX id ON sponsors (id)');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3A977936C');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3727ACA70');
        $this->addSql('DROP INDEX IDX_1D1C63B3A977936C ON utilisateur');
        $this->addSql('DROP INDEX IDX_1D1C63B3727ACA70 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur CHANGE groupe_id groupe_id INT NOT NULL, CHANGE lft lft INT DEFAULT NULL, CHANGE lvl lvl INT DEFAULT NULL, CHANGE rgt rgt INT DEFAULT NULL');
    }
}

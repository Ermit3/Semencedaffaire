<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210227233546 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '[Création des Tables]';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE acl (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slide (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, text LONGTEXT DEFAULT NULL, soustext VARCHAR(255) DEFAULT NULL, afficher TINYINT(1) NOT NULL, upload_at DATETIME NOT NULL, create_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, titre VARCHAR(100) NOT NULL, text LONGTEXT NOT NULL, soustext VARCHAR(255) DEFAULT NULL, afficher TINYINT(1) NOT NULL, upload_at DATETIME NOT NULL, create_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, mail VARCHAR(255) NOT NULL, sujet VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, afficher TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, text LONGTEXT NOT NULL, afficher TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, filenameface VARCHAR(255) DEFAULT NULL, filenamedos VARCHAR(255) DEFAULT NULL, titre VARCHAR(100) NOT NULL, text LONGTEXT NOT NULL, soustext VARCHAR(255) DEFAULT NULL, afficher TINYINT(1) NOT NULL, upload_at DATETIME NOT NULL, create_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transition (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, titre VARCHAR(100) DEFAULT NULL, text LONGTEXT DEFAULT NULL, soustext VARCHAR(255) DEFAULT NULL, afficher TINYINT(1) NOT NULL, upload_at DATETIME NOT NULL, create_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE arriere_plan (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, nom VARCHAR(100) DEFAULT NULL, afficher TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, upload_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE renew_password (id INT AUTO_INCREMENT NOT NULL, mail VARCHAR(255) NOT NULL, newpassword VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, afficher TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(100) NOT NULL, mailsource VARCHAR(255) NOT NULL, maildestination VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, afficher TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, idrepondre INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, video VARCHAR(255) NOT NULL, titre VARCHAR(255) DEFAULT NULL, text LONGTEXT NOT NULL, afficher TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cotisations (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, montant DOUBLE PRECISION NOT NULL, create_at DATETIME NOT NULL, afficher TINYINT(1) NOT NULL, facture INT NOT NULL, idfiche INT DEFAULT NULL, quartier VARCHAR(255) DEFAULT NULL, telephone INT DEFAULT NULL, nationalite VARCHAR(255) DEFAULT NULL, nomsponsor VARCHAR(100) DEFAULT NULL, prenomsponsor VARCHAR(100) DEFAULT NULL, telephonesponsor INT DEFAULT NULL, idsponsor INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news_letter (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, mail VARCHAR(255) NOT NULL, id_contact INT NOT NULL, afficher TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(255) DEFAULT NULL, titre VARCHAR(100) NOT NULL, text LONGTEXT NOT NULL, soustext VARCHAR(255) DEFAULT NULL, afficher TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, login VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', montant DOUBLE PRECISION NOT NULL, statut VARCHAR(100) NOT NULL, afficher TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE presentation (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(255) DEFAULT NULL, titre VARCHAR(100) DEFAULT NULL, soustitre VARCHAR(255) DEFAULT NULL, texte LONGTEXT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, afficher TINYINT(1) NOT NULL, create_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE acl');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE slide');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE transition');
        $this->addSql('DROP TABLE arriere_plan');
        $this->addSql('DROP TABLE renew_password');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP TABLE cotisations');
        $this->addSql('DROP TABLE news_letter');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE presentation');
    }
}

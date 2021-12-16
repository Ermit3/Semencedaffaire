<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210228090500 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '[Création des Liaisons entre Tables]';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateur_acl (utilisateur_id INT NOT NULL, acl_id INT NOT NULL, INDEX IDX_31CCD161FB88E14F (utilisateur_id), INDEX IDX_31CCD16144082458 (acl_id), PRIMARY KEY(utilisateur_id, acl_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utilisateur_acl ADD CONSTRAINT FK_31CCD161FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_acl ADD CONSTRAINT FK_31CCD16144082458 FOREIGN KEY (acl_id) REFERENCES acl (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE utilisateur ADD groupe_id INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B37A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B37A45358C ON utilisateur (groupe_id)');

        $this->addSql('ALTER TABLE utilisateur ADD grade_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3FE19A1A8 FOREIGN KEY (grade_id) REFERENCES grade (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B3FE19A1A8 ON utilisateur (grade_id)');

        $this->addSql('ALTER TABLE utilisateur ADD source_id INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3953C1C61 FOREIGN KEY (source_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3953C1C61 ON utilisateur (source_id)');

        $this->addSql('ALTER TABLE cotisations ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cotisations ADD facture INT NOT NULL, ADD idfiche INT DEFAULT NULL, ADD quartier VARCHAR(255) DEFAULT NULL, ADD telephone INT DEFAULT NULL, ADD nationalite VARCHAR(255) DEFAULT NULL, ADD nomsponsor VARCHAR(100) DEFAULT NULL, ADD prenomsponsor VARCHAR(100) DEFAULT NULL, ADD telephonesponsor INT DEFAULT NULL, ADD idsponsor INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cotisations ADD CONSTRAINT FK_C51B351C953C1C61 FOREIGN KEY (source_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_C51B351C953C1C61 ON cotisations (source_id)');

        $this->addSql('ALTER TABLE news_letter ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news_letter ADD CONSTRAINT FK_2AB2D7E953C1C61 FOREIGN KEY (source_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_2AB2D7E953C1C61 ON news_letter (source_id)');

        $this->addSql('ALTER TABLE projet ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9953C1C61 FOREIGN KEY (source_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_50159CA9953C1C61 ON projet (source_id)');

        $this->addSql('ALTER TABLE renew_password ADD source_id INT NOT NULL');
        $this->addSql('ALTER TABLE renew_password ADD CONSTRAINT FK_C0186BF4953C1C61 FOREIGN KEY (source_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_C0186BF4953C1C61 ON renew_password (source_id)');

        $this->addSql('ALTER TABLE arriere_plan ADD source_id INT NOT NULL');
        $this->addSql('ALTER TABLE arriere_plan ADD CONSTRAINT FK_9188422F953C1C61 FOREIGN KEY (source_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_9188422F953C1C61 ON arriere_plan (source_id)');

        $this->addSql('ALTER TABLE video ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C953C1C61 FOREIGN KEY (source_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C953C1C61 ON video (source_id)');

        $this->addSql('ALTER TABLE reponse ADD contact_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC7E7A1254A ON reponse (contact_id)');

        $this->addSql('ALTER TABLE reponse ADD commentaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7BA9CD190 FOREIGN KEY (commentaire_id) REFERENCES commentaire (id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC7BA9CD190 ON reponse (commentaire_id)');

        $this->addSql('ALTER TABLE produit ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27953C1C61 FOREIGN KEY (source_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27953C1C61 ON produit (source_id)');

        $this->addSql('ALTER TABLE article ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66953C1C61 FOREIGN KEY (source_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66953C1C61 ON article (source_id)');

        $this->addSql('ALTER TABLE transition ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transition ADD CONSTRAINT FK_F715A75A953C1C61 FOREIGN KEY (source_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_F715A75A953C1C61 ON transition (source_id)');

        $this->addSql('ALTER TABLE slide ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slide ADD CONSTRAINT FK_72EFEE62953C1C61 FOREIGN KEY (source_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_72EFEE62953C1C61 ON slide (source_id)');

        $this->addSql('ALTER TABLE reponse ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7953C1C61 FOREIGN KEY (source_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC7953C1C61 ON reponse (source_id)');
    }

    public function down(Schema $schema) : void
    {
        // $this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE utilisateur_acl');

        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B37A45358C');
        $this->addSql('DROP INDEX IDX_1D1C63B37A45358C ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP groupe_id');

        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3FE19A1A8');
        $this->addSql('DROP INDEX IDX_1D1C63B3FE19A1A8 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP grade_id');

        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3953C1C61');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3953C1C61 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP source_id');

        $this->addSql('ALTER TABLE cotisations DROP FOREIGN KEY FK_C51B351C953C1C61');
        $this->addSql('DROP INDEX IDX_C51B351C953C1C61 ON cotisations');
        $this->addSql('ALTER TABLE cotisations DROP source_id');

        $this->addSql('ALTER TABLE news_letter DROP FOREIGN KEY FK_2AB2D7E953C1C61');
        $this->addSql('DROP INDEX IDX_2AB2D7E953C1C61 ON news_letter');
        $this->addSql('ALTER TABLE news_letter DROP source_id');

        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9953C1C61');
        $this->addSql('DROP INDEX IDX_50159CA9953C1C61 ON projet');
        $this->addSql('ALTER TABLE projet DROP source_id');

        $this->addSql('ALTER TABLE renew_password DROP FOREIGN KEY FK_C0186BF4953C1C61');
        $this->addSql('DROP INDEX IDX_C0186BF4953C1C61 ON renew_password');
        $this->addSql('ALTER TABLE renew_password DROP source_id');

        $this->addSql('ALTER TABLE arriere_plan DROP FOREIGN KEY FK_9188422F953C1C61');
        $this->addSql('DROP INDEX IDX_9188422F953C1C61 ON arriere_plan');
        $this->addSql('ALTER TABLE arriere_plan DROP source_id');

        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C953C1C61');
        $this->addSql('DROP INDEX IDX_7CC7DA2C953C1C61 ON video');
        $this->addSql('ALTER TABLE video DROP source_id');

        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7E7A1254A');
        $this->addSql('DROP INDEX IDX_5FB6DEC7E7A1254A ON reponse');
        $this->addSql('ALTER TABLE reponse DROP contact_id');

        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7BA9CD190');
        $this->addSql('DROP INDEX IDX_5FB6DEC7BA9CD190 ON reponse');
        $this->addSql('ALTER TABLE reponse DROP commentaire_id');

        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27953C1C61');
        $this->addSql('DROP INDEX IDX_29A5EC27953C1C61 ON produit');
        $this->addSql('ALTER TABLE produit DROP source_id');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66953C1C61');
        $this->addSql('DROP INDEX IDX_23A0E66953C1C61 ON article');
        $this->addSql('ALTER TABLE article DROP source_id');

        $this->addSql('ALTER TABLE transition DROP FOREIGN KEY FK_F715A75A953C1C61');
        $this->addSql('DROP INDEX IDX_F715A75A953C1C61 ON transition');
        $this->addSql('ALTER TABLE transition DROP source_id');

        $this->addSql('ALTER TABLE slide DROP FOREIGN KEY FK_72EFEE62953C1C61');
        $this->addSql('DROP INDEX IDX_72EFEE62953C1C61 ON slide');
        $this->addSql('ALTER TABLE slide DROP source_id');

        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7953C1C61');
        $this->addSql('DROP INDEX IDX_5FB6DEC7953C1C61 ON reponse');
        $this->addSql('ALTER TABLE reponse DROP source_id');
    }
}

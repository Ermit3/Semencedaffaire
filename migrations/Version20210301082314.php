<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210301082314 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '[Insertion des Données]';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `acl` (`id`, `nom`) VALUES (1, 'Add')");
        $this->addSql("INSERT INTO `acl` (`id`, `nom`) VALUES (2, 'Edit')");
        $this->addSql("INSERT INTO `acl` (`id`, `nom`) VALUES (3, 'Delete')");
        $this->addSql("INSERT INTO `acl` (`id`, `nom`) VALUES (4, 'Article')");
        $this->addSql("INSERT INTO `acl` (`id`, `nom`) VALUES (5, 'Reponse')");
        $this->addSql("INSERT INTO `acl` (`id`, `nom`) VALUES (6, 'Commentaire')");
        $this->addSql("INSERT INTO `acl` (`id`, `nom`) VALUES (7, 'Utilisateurs')");
        $this->addSql("INSERT INTO `acl` (`id`, `nom`) VALUES (8, 'Administrateur')");

        $this->addSql("INSERT INTO `grade` (`id`, `nom`) VALUES (1, 'Aucune Etoile')");
        $this->addSql("INSERT INTO `grade` (`id`, `nom`) VALUES (2, 'Une Etoiles')");
        $this->addSql("INSERT INTO `grade` (`id`, `nom`) VALUES (3, 'Deux Etoiles')");
        $this->addSql("INSERT INTO `grade` (`id`, `nom`) VALUES (4, 'Trois Etoiles')");
        $this->addSql("INSERT INTO `grade` (`id`, `nom`) VALUES (5, 'Quatre Etoiles')");
        $this->addSql("INSERT INTO `grade` (`id`, `nom`) VALUES (6, 'Cinque Etoiles')");
        $this->addSql("INSERT INTO `grade` (`id`, `nom`) VALUES (7, 'Six Etoiles')");
        $this->addSql("INSERT INTO `grade` (`id`, `nom`) VALUES (8, 'Sept Etoiles')");

        $this->addSql("INSERT INTO `groupe` (`id`, `nom`) VALUES (1, '100 Milles')");
        $this->addSql("INSERT INTO `groupe` (`id`, `nom`) VALUES (2, '500 Milles')");
        $this->addSql("INSERT INTO `groupe` (`id`, `nom`) VALUES (3, '1 Million')");

        $this->addSql("INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `login`, `password`, `mail`, `roles`, `montant`, `statut`, `afficher`, `groupe_id`, `grade_id`, `source_id`) VALUES (1, 'BEKALE ONAH','Stephane Regis','Akobisoft','$2y$12\$lXRBRZm.dwUqEhtRmfnLQOsDnYatoa3HvBPGFNzDUUht9IjxZNHyG','stephanebekale@akobisoft.com','a:1:{i:0;s:10:\"ROLE_ADMIN\";}',100000.00,1,1,1,1,1)");
        $this->addSql("INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `login`, `password`, `mail`, `roles`, `montant`, `statut`, `afficher`, `groupe_id`, `grade_id`, `source_id`) VALUES (2, 'OBIANG','Thierry Herve','e.Service','$2y$12\$SS3YdPlli/hoXlNbWZMbSOfLAHcXsZjCkjjrFw4z0rEJ1tTKCskFu','e.service@semencedaffaires.com','a:1:{i:0;s:10:\"ROLE_ADMIN\";}',500000.00,2,1,2,2,2)");

        $this->addSql("INSERT INTO `utilisateur_acl` (`utilisateur_id`, `acl_id`) VALUES (1, 1)");
        $this->addSql("INSERT INTO `utilisateur_acl` (`utilisateur_id`, `acl_id`) VALUES (1, 2)");

        $this->addSql("INSERT INTO `arriere_plan` (`id`,`filename`,`nom`,`afficher`,`create_at`,`upload_at`,`source_id`) VALUES (1,'f1a05fe130b2881496162.webp','Bureau',1,'2021-03-01 11:48:24','2021-03-01 11:48:28',1)");
        $this->addSql("INSERT INTO `arriere_plan` (`id`,`filename`,`nom`,`afficher`,`create_at`,`upload_at`,`source_id`) VALUES (2,'f1a09e9deb6f530707840.webp','Cuisine',1,'2021-03-01 11:49:47','2021-03-01 11:49:49',1)");

        $this->addSql("INSERT INTO `article` (`id`,`filename`,`titre`,`text`,`soustext`,`afficher`,`upload_at`,`create_at`,`source_id`) VALUES (1,'5f3f0e82d9c78614513501.webp','qu est-ce que c est','Aussi appelée “clause d accroissement, la tontine est une clause insérée dans un contrat d acquisition en commun qui stipule que le dernier  ','Inventé par Lorenzo Tonti en 1653, le pacte tontinier est une convention qui intervient entre ',1,'2021-03-01 11:51:36','2021-03-01 11:51:38',1)");
        $this->addSql("INSERT INTO `article` (`id`,`filename`,`titre`,`text`,`soustext`,`afficher`,`upload_at`,`create_at`,`source_id`) VALUES (2,'5f3f0e82d9c78614513501.webp','Carateristique','Aussi appelée “clause d accroissement, la tontine est une clause insérée dans un contrat d acquisition en commun qui stipule que le dernier vivant','le pacte est dissout et le capital constitué réparti entre les bénéficiaires toujours en vie.',1,'2021-03-01 11:51:36','2021-03-01 11:51:38',1)");
        $this->addSql("INSERT INTO `article` (`id`,`filename`,`titre`,`text`,`soustext`,`afficher`,`upload_at`,`create_at`,`source_id`) VALUES (3,'5f3f0e82d9c78614513501.webp','Tontine financière','Aussi appelée “clause d accroissement, la tontine est une clause insérée dans un contrat d acquisition en commun qui stipule  valeurs . A terme','le pacte est dissout et le capital constitué réparti entre les bénéficiaires toujours en vie.',1,'2021-03-01 11:51:36','2021-03-01 11:51:38',1)");

        $this->addSql("INSERT INTO `commentaire` (`id`,`nom`,`prenom`,`mail`,`sujet`,`text`,`afficher`,`create_at`) VALUES (1,'BEKALE ONAH','Stephane','stephanebekale@akobisoft.com','Tontine','La tontine financière constitue un produit de diversification patrimonial permettant la constitution d’un capital, tout en misant sur le fait qu’en cas de décès de l’un des tontiniers, sa part ira aux survivants.',1,'2021-03-01 12:01:49')");
        $this->addSql("INSERT INTO `commentaire` (`id`,`nom`,`prenom`,`mail`,`sujet`,`text`,`afficher`,`create_at`) VALUES (2,'OBIANG','Thierry','e.service@semencedaffaire.com','Versement','Elle peut être utilisée comme instrument de transmission intergénérationnelle. Les sommes concernées sont alors investies en tontine au nom du bénéficiaire (par exemple à un enfant ou un petit enfant) dans le cadre d’une donation. Cette formule ouvrant droit à des abattements fiscaux (jusqu’à 100.000 euros tous les 15 ans).',1,'2021-03-01 12:02:51')");

        $this->addSql("INSERT INTO `contact` (`id`,`nom`,`prenom`,`text`,`afficher`,`create_at`,`mail`) VALUES (1,'BEKALE','Stephane','A condition d’avoir souscrit des tontines “en cascade”, il est éventuellement possible de profiter de cet avantage une fois par an. En revanche, la tontine n’offre aucun avantage spécifique d’un point de vue successoral',1,'2021-03-01 12:18:46','e.service@semencedaffaire.com')");

        $this->addSql("INSERT INTO `cotisations` (`id`,`source_id`,`nom`,`prenom`,`montant`,`create_at`,`afficher`) VALUES (1,1,'OBIANG','Thierry',200000,'2021-03-01 12:02:51',1)");

        $this->addSql("INSERT INTO `news_letter` (`id`,`nom`,`prenom`,`mail`,`id_contact`,`afficher`,`source_id`,`create_at`) VALUES (1,'OBIANG','Thierry','e.service@semencedaffaire.com',1,1,1,'2021-03-01 12:02:51')");

        $this->addSql("INSERT INTO `produit` (`id`,`filenameface`,`filenamedos`,`titre`,`text`,`soustext`,`afficher`,`upload_at`,`create_at`,`source_id`) VALUES (1,'5f3f0e82d9c78614513501.webp','5f3f0e82d9c78614513501.webp','Cuisine','La tontine financière constitue un produit de diversification patrimonial permettant la constitution d’un capital, tout en misant sur le fait qu’en cas de décès de l’un des tontiniers, sa part ira aux survivants.','Elle peut être utilisée comme instrument de transmission intergénérationnelle',1,'2021-03-01 12:18:44','2021-03-01 12:18:46',1)");

        $this->addSql("INSERT INTO `projet` (`id`,`source_id`,`filename`,`titre`,`text`,`soustext`,`afficher`,`create_at`) VALUES (1,1,'5f3f0e82d9c78614513501.webp','Accroissement','La tontine financière constitue un produit de diversification patrimonial permettant la constitution d’un capital, tout en misant sur le fait sa part ira aux survivants.','Elle peut être utilisée comme instrument de transmission intergénérationnelle',1,'2021-03-01 12:18:46')");

        $this->addSql("INSERT INTO `reponse` (`id`,`contact_id`,`commentaire_id`,`source_id`,`type`,`mailsource`,`maildestination`,`text`,`afficher`,`create_at`,`idrepondre`) VALUES (1,1,1,1,'Vision','e.service@semencedaffaires.com','stephanebekale@akobisoft.com','La tontine financière constitue un produit de diversification patrimonial permettant la  aux survivants.',1,'2021-03-01 12:18:46',1)");

        $this->addSql("INSERT INTO `slide` (`id`,`source_id`,`filename`,`text`,`soustext`,`afficher`,`upload_at`,`create_at`) VALUES (1,1,'5f1a09e9deb6f530707840.webp','La tontine financière constitue un produit de diversification patrimonial permettant la  aux survivants.','produit de diversification patrimonial permettant la constitution d’un capital',1,'2021-03-01 12:18:46','2021-03-01 12:27:24')");
        $this->addSql("INSERT INTO `slide` (`id`,`source_id`,`filename`,`text`,`soustext`,`afficher`,`upload_at`,`create_at`) VALUES (2,1,'5f1a116c6c161450763263.webp','La tontine financière constitue un produit de diversification patrimonial permettant la  aux survivants.','produit de diversification patrimonial permettant la constitution d’un capital',1,'2021-03-01 12:18:46','2021-03-01 12:27:24')");
        $this->addSql("INSERT INTO `slide` (`id`,`source_id`,`filename`,`text`,`soustext`,`afficher`,`upload_at`,`create_at`) VALUES (3,1,'5f1a0a7d9f3c3481629231.webp','La tontine financière constitue un produit de diversification patrimonial permettant la  aux survivants.','produit de diversification patrimonial permettant la constitution d’un capital',1,'2021-03-01 12:18:46','2021-03-01 12:27:24')");

        $this->addSql("INSERT INTO `transition` (`id`,`filename`,`titre`,`text`,`soustext`,`afficher`,`upload_at`,`create_at`,`source_id`) VALUES (1,'Akobisoft.webp','Tontine financière','La tontine financière constitue un produit de diversification patrimonial permettant la  aux survivants.','produit de diversification patrimonial permettant la constitution d’un capital',1,'2021-03-01 12:18:46','2021-03-01 12:27:24',1)");
        $this->addSql("INSERT INTO `transition` (`id`,`filename`,`titre`,`text`,`soustext`,`afficher`,`upload_at`,`create_at`,`source_id`) VALUES (2,'Akobisoft.webp','Caractéristiques de la tontine','La tontine financière constitue un produit de diversification patrimonial permettant la  aux survivants.','produit de diversification patrimonial permettant la constitution d’un capital',1,'2021-03-01 12:18:46','2021-03-01 12:27:24',1)");

        $this->addSql("INSERT INTO `video` (`id`,`source_id`,`video`,`titre`,`text`,`afficher`,`create_at`) VALUES (1,1,'https://youtu.be/G2bx9iBfm2s','Tontine financière','La tontine financière constitue un produit de diversification patrimonial permettant la  aux survivants.',1,'2021-03-01 12:18:46')");

        $this->addSql("INSERT INTO `renew_password` (`id`,`mail`,`newpassword`,`create_at`,`afficher`,`source_id`) VALUES (1,'stephanebekale@yahoo.fr','$2y$12\$lXRBRZm.dwUqEhtRmfnLQOsDnYatoa3HvBPGFNzDUUht9IjxZNHyG','2021-03-01 12:18:46',1,1)");

    }

    public function down(Schema $schema) : void
    {

        $this->addSql("TRUNCATE `utilisateur_acl`;");
        $this->addSql("TRUNCATE `acl`;");
        $this->addSql("TRUNCATE `grade`;");
        $this->addSql("TRUNCATE `groupe`;");
        $this->addSql("TRUNCATE `utilisateur`;");
        $this->addSql("TRUNCATE `video`;");
        $this->addSql("TRUNCATE `transition`;");
        $this->addSql("TRUNCATE `slide`;");
        $this->addSql("TRUNCATE `reponse`;");
        $this->addSql("TRUNCATE `projet`;");
        $this->addSql("TRUNCATE `produit`;");
        $this->addSql("TRUNCATE `news_letter`;");
        $this->addSql("TRUNCATE `cotisations`;");
        $this->addSql("TRUNCATE `contact`;");
        $this->addSql("TRUNCATE `commentaire`;");
        $this->addSql("TRUNCATE `article`;");
        $this->addSql("TRUNCATE `arriere_plan`;");
    }
}

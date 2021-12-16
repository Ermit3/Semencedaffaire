<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210306212754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cotisations ADD facture INT NOT NULL, ADD idfiche INT DEFAULT NULL, ADD quartier VARCHAR(255) DEFAULT NULL, ADD telephone INT DEFAULT NULL, ADD nationalite VARCHAR(255) DEFAULT NULL, ADD nomsponsor VARCHAR(100) DEFAULT NULL, ADD prenomsponsor VARCHAR(100) DEFAULT NULL, ADD telephonesponsor INT DEFAULT NULL, ADD idsponsor INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cotisations DROP facture, DROP idfiche, DROP quartier, DROP telephone, DROP nationalite, DROP nomsponsor, DROP prenomsponsor, DROP telephonesponsor, DROP idsponsor');
    }
}

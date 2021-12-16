<?php

namespace App\Services;

use App\Entity\CommandeClient;
use App\Repository\CommandeClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CommandesService
 *
 * Fournisseur de services dédiés à la Gestion des CommandesClient
 *
 * @package App\Services
 */
class CommandesClientService {


    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CommandeClientRepository
     */
    private $commandeClientRepository;

    public function __construct(CommandeClientRepository $commandeClientRepository, EntityManagerInterface $em){
        $this->commandeClientRepository = $commandeClientRepository;
        $this->em = $em;
    }

    /**
     * Test l'existenec des CommandeClients
     *
     * @return bool
     */
    public function CommandeClientExist(): bool
    {
        return [] != $this->commandeClientRepository->findAll();
    }

    #region Verifie qu'une CommandeClient Existe : Bool
    /**
     * Vérifie l'Existence d'une CommandeClient via son Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->commandeClientRepository->findBy(['id'=>$id]);
    }
    #endregion CommandeClient Existe

    /**
     * Retourn tous les LigCommandeClientComande
     *
     * @return array
     */
    public function allCommandeClient(): array
    {
       if (!$this->CommandeClientExist()){
           throw new NotFoundHttpException("Il n'y a pas de CommandClient en Base !");
       }
       return $this->commandeClientRepository->findAll();
    }
    #region Recuperer un CommandClient
    /**
     * Retourne une CommandClient
     *
     * @param $id
     *
     * @return CommandeClient
     */
    public function CommandeClient($id): CommandeClient
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cette CommandeClientExist n'existe pas !");
        }
        return $this->commandeClientRepository->findOneBy(['id'=>$id]);
    }
    #endregion


    #region Recupere une CommandeClient via Id et Titre
    /**
     * @param int    $id
     * @param string $titre
     *
     * @return CommandeClient
     */
    public function findCommandeClientIdTitre(int $id, string $titre): CommandeClient
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cette CommandeClient n'existe pas !");
        }
        return $this->commandeClientRepository->findOneBy(['id'=>$id, 'titre'=>$titre]);
    }
    #endregion

    #region pagination
    /**
     * Pour la pagination des CommandeClient
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->commandeClientRepository->findLigneComandePaginatorQuery();
    }
    #endregion

    #region Controle l'existence d'1 CommandeClient via Titre et image
    /**
     * Controle l'existence d'un CommandeClient en fonction du Titre et Image
     *
     * @param int $id
     * @param string $nomProduit
     *
     * @return bool
     */
    public function ligneComandeExisteTitreImage(int $id, string $nomProduit):bool
    {
        return [] != $this->commandeClientRepository->findBy(['id' =>$id, 'nomProduit' =>$nomProduit]);
    }
    #endregion

//== Administration

    #region Editer un Produit
    /**
     * @param CommandeClient $ligneComande
     *
     * @return CommandeClient
     * @throws Exception
     */
    public function editProduit(CommandeClient $ligneComande): CommandeClient
    {
        if (!$ligneComande){
            throw new NotFoundHttpException("Cette $ligneComande n'existe pas !");
        }
        try {
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de l'Edition de Ce $ligneComande !" . $exception->getMessage());
        }
        return $ligneComande;
    }
    #endregion

    #region Supprimer un Produit
    /**
     * Supprimer un Produit
     *
     * @param int $id
     *
     * @return CommandeClient
     * @throws Exception
     */
    public function deleteProduit(int $id): CommandeClient
    {
        if (!$this->exist($id)){
            throw new Exception("Ce Produit n'existe pas !");
        }
        try {
            $ligneComande = $this->ligneComande($id);
            $this->em->remove($ligneComande);
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de la Suppression de Ce LigneComande !" . $exception->getMessage());
        }
        return $ligneComande;
    }
    #endregion

//== Fin Administration
}
<?php

namespace App\Services;

use App\Entity\LigneComande;
use App\Repository\LigneComandeRepository;
use App\Repository\ProduitRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CommandesService
 *
 * Fournisseur de services dédiés à la Gestion des Commandes
 *
 * @package App\Services
 */
class LigneCommandesService {

    /**
     * @var ProduitRepository
     */
    private $produitRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var LigneComandeRepository
     */
    private $ligneComandeRepository;

    public function __construct(LigneComandeRepository $ligneComandeRepository, EntityManagerInterface $em){
        $this->ligneComandeRepository = $ligneComandeRepository;
        $this->em = $em;
    }

    /**
     * test l'existenec des LigneComande
     *
     * @return bool
     */
    public function ligneComandeExist(): bool
    {
        return [] != $this->ligneComandeRepository->findAll();
    }

    #region Verifie qu'une LigneComande Existe : Bool
    /**
     * Vérifie l'Existence d'un LigneComande via son Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->ligneComandeRepository->findBy(['id'=>$id]);
    }
    #endregion LigneComande Existe


    #region Verifie qu'une LigneComande Existe : Bool
    /**
     * Vérifie l'Existence d'un LigneComande via son Id
     *
     * @param $id
     *
     * @return bool
     */
    public function commandeExist($id): bool
    {
        return [] != $this->ligneComandeRepository->findBy(['commande'=>$id]);
    }
    #endregion LigneComande Existe

    /**
     * Retourn tous les LigneComande
     *
     * @return array
     */
    public function allLigneComande(): array
    {
       return $this->ligneComandeRepository->findAll();
    }
    #region Recuperer un LigneComande
    /**
     * Retourne un LigneComande
     *
     * @param $id
     *
     * @return LigneComande
     */
    public function ligneComande($id): LigneComande
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cette Ligne de Commande n'existe pas !");
        }
        return $this->ligneComandeRepository->findOneBy(['id'=>$id]);
    }
    #endregion


    #region Recupere un LigneComande via Id et Titre
    /**
     * @param int    $id
     * @param string $titre
     *
     * @return LigneComande
     */
    public function findLigneComandeIdTitre(int $id, string $titre): LigneComande
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cette LigneComande n'existe pas !");
        }
        return $this->ligneComandeRepository->findOneBy(['id'=>$id, 'titre'=>$titre]);
    }
    #endregion

    #region pagination
    /**
     * Pour la pagination des LigneComande
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->ligneComandeRepository->findLigneComandePaginatorQuery();
    }
    #endregion

    #region Controle l'existence d'1 LigneComande via Titre et image
    /**
     * Controle l'existence d'un LigneComande en fonction du Titre et Image
     *
     * @param int $id
     * @param string $nomProduit
     *
     * @return bool
     */
    public function ligneComandeExisteTitreImage(int $id, string $nomProduit):bool
    {
        return [] != $this->ligneComandeRepository->findBy(['id' =>$id, 'nomProduit' =>$nomProduit]);
    }
    #endregion

//== Administration

    #region Ajouter un ligneComande : Initialiser
    /**
     * @return LigneComande
     */
    public function initProduit(): LigneComande
    {
        return  (new LigneComande())
            ->setProduit(null)
            ->setCommande(null)
            ->setNomProduit('Sans Nom')
            ->setPrixCommande(0.00)
            ->setQuantite(0)
            ->setTotal(0);
    }
    #endregion

    #region Ajouter un Produit
    /**
     * @param LigneComande $LigneComande
     *
     * @return LigneComande
     * @throws Exception
     */
    public function addProduit(LigneComande $LigneComande): LigneComande
    {
        if ($this->ligneComandeExisteTitreImage($LigneComande->getId(), $LigneComande->getNomProduit())){
            throw new NotFoundHttpException("Cet LigneComande existe déjà !");
        }
        try {
            $this->em->persist($LigneComande);
            $this->em->flush();
        }catch (Exception $exception){
            throw new Exception("Erreur lors de l'enregistrement de la LigneComande" . $exception->getMessage());
        }
        return $LigneComande;
    }
    #endregion

    #region Editer un Produit
    /**
     * @param LigneComande $ligneComande
     *
     * @return LigneComande
     * @throws Exception
     */
    public function editProduit(LigneComande $ligneComande): LigneComande
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
     * @return LigneComande
     * @throws Exception
     */
    public function deleteProduit(int $id): LigneComande
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

    #region Controle l'existence d'1 ligneComande via Titre et image
    /**
     * Controle l'existence d'une ligneComande de Produit
     *
     * @param int $id
     *
     * @return array
     */
    public function alligneComandeByCat(int $id):array
    {
        return $this->ligneComandeRepository->findBy(['categorie' =>$id]);
    }
    #endregion

    #region Controle l'existence d'1 ligneComande via Titre et image
    /**
     * Controle l'existence d'une ligneComande de Produit
     *
     * @param int $id
     *
     * @return array
     */
    public function ligneComandeByUser(int $id):array
    {
        return $this->ligneComandeRepository->findLignComByUser($id);
    }
    #endregion


    #region Controle l'existence d'1 ligneComande via un Id
    /**
     * Controle l'existence d'une ligneComande de Produit
     *
     * @param int $id
     *
     * @return LigneComande
     * @throws Exception
     */
    public function ligneComandeByCommandeId(int $id):LigneComande
    {
        if (!$this->commandeExist($id)){
            throw new Exception("Cette commande n'existe pas!");
        }
        return $this->ligneComandeRepository->findOneBy(['commande'=>$id]);
    }
    #endregion


    #region Supprimer une LigneComande
    /**
     * Supprimer une LigneComande
     *
     * @param int $id
     *
     * @return LigneComande
     * @throws Exception
     */
    public function deleteLigneComande(int $id): LigneComande
    {
        if (!$this->exist($id)){
            throw new Exception("Cette LigneComande n'existe pas !");
        }
        try {
            $ligneCommande = $this->ligneComande($id);
            $this->em->remove($ligneCommande);
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de la Suppression de Cette ligne de commande !" . $exception->getMessage());
        }
        return $ligneCommande;
    }
    #endregion
//== Fin Administration
}
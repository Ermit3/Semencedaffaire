<?php

namespace App\Services;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AccueilService
 *
 * Fournisseur de services dédiés à la Gestion de l'Accueil
 *
 * @package App\Services
 */
class CalculeGainsService {

    /**
     * @var ProduitRepository
     */
    private $produitRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;

    public function __construct(ProduitRepository $produitRepository, EntityManagerInterface $em, UtilisateurService $utilisateurService){
        $this->produitRepository = $produitRepository;
        $this->utilisateurService = $utilisateurService;
        $this->em = $em;
    }

    /**
     * test l'existenec des produits
     *
     * @return bool
     */
    public function produitExist(): bool
    {
        return [] != $this->produitRepository->findAll();
    }

    #region Verifie qu'un Article Existe : Bool
    /**
     * Vérifie l'Existence d'un Produit via son Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->produitRepository->findBy(['id'=>$id]);
    }
    #endregion Produit Existe

    /**
     * Retourn tous les produits
     *
     * @return array
     */
    public function allProduits(): array
    {
       if (!$this->produitExist()){
           throw new NotFoundHttpException("Il n'y a pas de Produits en Base !");
       }
       return $this->produitRepository->findAll();
    }
    #region Recuperer un Article
    /**
     * Retourne un Article
     *
     * @param $id
     *
     * @return Produit
     */
    public function produit($id): Produit
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cet Produit n'existe pas !");
        }
        return $this->produitRepository->findOneBy(['id'=>$id]);
    }
    #endregion

    #region pagination
    /**
     * Pour la pagination des Produits
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->produitRepository->findProduitPaginatorQuery();
    }
    #endregion


    #region Controle l'existence d'1 Produit via Titre et image
    /**
     * Controle l'existence d'un Produit en fonction du Titre et Image
     *
     * @param string $titre
     * @param string $image
     *
     * @return bool
     */
    public function produitExisteTitreImage(string $titre, string $image):bool
    {
        return [] != $this->produitRepository->findBy(['titre' =>$titre, 'filenameface' =>$image]);
    }
    #endregion

//== Administration

    #region Ajouter un Produit : Initialiser
    /**
     * @return Produit
     */
    public function initProduit(): Produit
    {
        return  (new Produit())
            ->setFilenameface('Nouvelle Image en Attente')
            ->setFilenamedos('Nouvelle Image en Attente')
            ->setTitre('')
            ->setText('')
            ->setSoustext('')
            ->setAfficher('')
            ->setUploadAt(new DateTime('now'))
            ->setCreateAt(new DateTime('now'))
            ->setSource(null);
    }
    #endregion

    #region Ajouter un Produit
    /**
     * @param Produit $produit
     *
     * @return Produit
     * @throws Exception
     */
    public function addProduit(Produit $produit): Produit
    {
        if ($this->produitExisteTitreImage($produit->getTitre(), $produit->getFilenameface())){
            throw new NotFoundHttpException("Cet Produit existe déjà !");
        }
        try {
            $this->em->persist($produit);
            $this->em->flush();
        }catch (Exception $exception){
            throw new Exception("Erreur lors de l'enregistrement du Produit" . $exception->getMessage());
        }
        return $produit;
    }
    #endregion

    #region Editer un Produit
    /**
     * @param Produit $produit
     *
     * @return Produit
     * @throws Exception
     */
    public function editProduit(Produit $produit): Produit
    {
        if (!$produit){
            throw new NotFoundHttpException("Ce Produit n'existe pas !");
        }
        try {
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de l'Edition de Ce Produit !" . $exception->getMessage());
        }
        return $produit;
    }
    #endregion

    #region Supprimer un Produit
    /**
     * Supprimer un Produit
     *
     * @param int $id
     *
     * @return Produit
     * @throws Exception
     */
    public function deleteProduit(int $id): Produit
    {
        if (!$this->exist($id)){
            throw new Exception("Ce Produit n'existe pas !");
        }
        try {
            $produit = $this->produit($id);
            $this->em->remove($produit);
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de la Suppression de Ce Produit !" . $exception->getMessage());
        }
        return $produit;
    }
    #endregion
//== Fin Administration
}
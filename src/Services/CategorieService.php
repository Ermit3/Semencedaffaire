<?php

namespace App\Services;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class CategorieService
 *
 * Fournisseur de services dédiés à la Gestion des CategorieServices
 *
 * @package App\Services
 */
class CategorieService {

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(CategorieRepository $categorieRepository, EntityManagerInterface $em){
        $this->categorieRepository = $categorieRepository;
        $this->em = $em;
    }

    /**
     * test l'existenec des categorie
     *
     * @return bool
     */
    public function categorieExist(): bool
    {
        return [] != $this->categorieRepository->findAll();
    }

    #region Verifie qu'une Categorie Existe : Bool
    /**
     * Vérifie l'Existence d'une Catégorie via son Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->categorieRepository->findBy(['id'=>$id]);
    }
    #endregion Catégorie Existe

    /**
     * Retourn tous les produits
     *
     * @return array
     */
    public function allCategorie(): array
    {
       if (!$this->categorieExist()){
           throw new NotFoundHttpException("Il n'y a pas de Catégorie en Base !");
       }
       return $this->categorieRepository->findBy(['afficher' => 1]);
    }
    #region Recuperer une Categorie
    /**
     * Retourne un Catégorie
     *
     * @param $id
     *
     * @return Categorie
     */
    public function categorie($id): Categorie
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cet Catégorie n'existe pas !");
        }
        return $this->categorieRepository->findOneBy(['id'=>$id]);
    }
    #endregion

    #region pagination
    /**
     * Pour la pagination des Catégorie
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->categorieRepository->findCategoriePaginatorQuery();
    }
    #endregion

    #region Controle l'existence d'une Catégorie via Nom
    /**
     * Controle l'existence d'une Catégorie en fonction du Nom
     *
     * @param string $titre
     *
     * @return bool
     */
    public function categorieExisteNom(string $titre):bool
    {
        return [] != $this->categorieRepository->findBy(['nom' =>$titre]);
    }
    #endregion

//== Administration

    #region Ajouter un Catégorie : Initialiser
    /**
     * @return Categorie
     */
    public function initCategorie(): Categorie
    {
        return  (new Categorie())
            ->setNom('Nouvelle Image en Attente')
            ->setUser(null)
            ->setCreatedAt(new DateTime('now'));
    }
    #endregion

    #region Ajouter un Catégorie
    /**
     * @param Categorie $categorie
     *
     * @return Categorie
     * @throws Exception
     */
    public function addCategori(Categorie $categorie): Categorie
    {
        if ($this->categorieExisteNom($categorie->getNom())){
            throw new NotFoundHttpException("Cette Catégorie existe déjà !");
        }
        try {
            $this->em->persist($categorie);
            $this->em->flush();
        }catch (Exception $exception){
            throw new Exception("Erreur lors de l'enregistrement de la Catégorie" . $exception->getMessage());
        }
        return $categorie;
    }
    #endregion

    #region Editer un Catégorie
    /**
     * @param Categorie $categorie
     *
     * @return Categorie
     * @throws Exception
     */
    public function editCategorie(Categorie $categorie): Categorie
    {
        if (!$categorie){
            throw new NotFoundHttpException("Ce Catégorie n'existe pas !");
        }
        try {
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de l'Edition de Ce Catégorie !" . $exception->getMessage());
        }
        return $categorie;
    }
    #endregion

    #region Supprimer un Catégorie
    /**
     * Supprimer un Catégorie
     *
     * @param int $id
     *
     * @return Categorie
     * @throws Exception
     */
    public function deleteCategorie(int $id): Categorie
    {
        if (!$this->exist($id)){
            throw new Exception("Ce Categorie n'existe pas !");
        }
        try {
            $categorie = $this->categorieRepository->findOneBy(['id'=>$id]);
            $categorie->setAfficher(0);
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de la Suppression de Cette Catégorie !" . $exception->getMessage());
        }
        return $categorie;
    }
    #endregion
//== Fin Administration
}
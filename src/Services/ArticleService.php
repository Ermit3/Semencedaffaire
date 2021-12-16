<?php

namespace App\Services;

use App\Entity\Article;
use App\Repository\ArticleRepository;
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
class ArticleService {

    /**
     * @var ArticleRepository
     */
    private $articleRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ArticleRepository $articleRepository, EntityManagerInterface $em){
        $this->articleRepository = $articleRepository;
        $this->em = $em;
    }

    #region Verifie qu'un Article Existe : Bool
    /**
     * Vérifie l'Existence d'un Article via son Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->articleRepository->findBy(['id'=>$id]);
    }
    #endregion Article Existe

    #region Verifie qu'un Article Existe : Bool
    /**
     * Vérifie l'Existence du Table d'Articles Qu'il ne soit pas vide
     *
     * @return bool
     */
    public function articleExist(): bool
    {
        return [] != $this->articleRepository->findAll();
    }
    #endregion Article Existe

    #region Verifie l'Existence du Tableau d'Article avec controle
    /**
     * Retourn tous les articles
     *
     * @return array
     */
    public function allArticle(): array
    {
       if (!$this->articleExist()){
           throw new NotFoundHttpException("Il n'y a pas d'Articles en Base !");
       }
       return $this->articleRepository->findAll();
    }
    #endregion Article Existe

    #region Controle l'existence d'1 Article via Titre et image
    /**
     * Controle l'existence d'un Article en fonction du Titre et Image
     *
     * @param string $titre
     * @param string $image
     *
     * @return bool
     */
    public function articleExisteTitreImage(string $titre, string $image):bool
    {
        return [] != $this->articleRepository->findBy(['titre' =>$titre, 'filename' =>$image]);
    }
    #endregion

    #region Recuperer un Article
    /**
     * Retourne un Article
     *
     * @param $id
     *
     * @return Article
     */
    public function article($id): Article
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cet Article n'existe pas !");
        }
        return $this->articleRepository->findOneBy(['id'=>$id]);
    }
    #endregion

    #region pagination
    /**
     * Pour la pagination des Articles
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->articleRepository->findArticlPaginatorQuery();
    }
    #endregion

//== Administration

    #region Ajouter un Article : Initialiser
    /**
     * @return Article
     */
    public function initArticle(): Article
    {
        return  (new Article())
            ->setFilename('Nouvelle Image en Attente')
            ->setTitre('')
            ->setText('')
            ->setText('')
            ->setSoustext('')
            ->setAfficher('')
            ->setUploadAt(new DateTime('now'))
            ->setCreateAt(new DateTime('now'))
            ->setSource(null);
    }
    #endregion

    #region Ajouter un Article
    /**
     * @param Article $article
     *
     * @return Article
     * @throws Exception
     */
    public function addArticle(Article $article): Article
    {
        if ($this->articleExisteTitreImage($article->getTitre(), $article->getFilename())){
            throw new NotFoundHttpException("Cet Article existe déjà !");
        }
        try {
            $this->em->persist($article);
            $this->em->flush();
        }catch (Exception $exception){
            throw new Exception("Erreur lors de l'enregistrement de l'Article" . $exception->getMessage());
        }
        return $article;
    }
    #endregion

    #region Editer un Article
    /**
     * @param Article $article
     *
     * @return Article
     * @throws Exception
     */
    public function editArticle(Article $article): Article
    {
        if (!$article){
            throw new NotFoundHttpException("Cet Article n'existe pas !");
        }
        try {
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de l'Edition de Cet Article !" . $exception->getMessage());
        }
        return $article;
    }
    #endregion

    #region Supprimer un Article
    /**
     * Supprimer un Article
     *
     * @param int $id
     *
     * @return Article
     * @throws Exception
     */
    public function deleteArticle(int $id): Article
    {
        if (!$this->exist($id)){
            throw new Exception("Cet Article n'existe pas !");
        }
        try {
            $article = $this->article($id);
            $this->em->remove($article);
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de la Suppression de Cet Article !" . $exception->getMessage());
        }
        return $article;
    }
    #endregion
//== Fin Administration
}
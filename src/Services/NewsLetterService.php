<?php

namespace App\Services;

use App\Entity\NewsLetter;
use App\Repository\NewsLetterRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints as Assert;

class NewsLetterService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var NewsLetterRepository
     */
    private $newsLetterRepository;

    public function __construct(
        NewsLetterRepository   $newsLetterRepository,
        EntityManagerInterface $em,
        Security               $security
    ){
        $this->newsLetterRepository = $newsLetterRepository;
        $this->em       = $em;
        $this->security = $security;
    }

    #region NewsLetter Existe : Bool
    /**
     * Vérifie l'Existence d'une NewsLetter via Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->newsLetterRepository->findBy(['id'=>$id]);
    }
    #endregion NewsLetter Existe

    #region NewsLetter Existent : Bool
    /**
     * Vérifie que le tableau NewsLetter ne soit pas vide
     *
     * @return bool
     */
    public function Exists(): bool
    {
        return [] != $this->newsLetterRepository->findAll();
    }
    #endregion NewsLetter Existent

    #region Recuperer une NewsLetter
    /**
     * Retourne une NewsLetter
     *
     * @param int $id
     *
     * @return NewsLetter
     */
    public function recupeNewsLetter(int $id): NewsLetter
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Ce NewsLetter n'existe pas !");
        }
        return $this->newsLetterRepository->findOneBy(['id'=>$id]);
    }
    #endregion NewsLetter Existe : Bool

    #region pagination
    /**
     * Pour la pagination des NewsLetter
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->newsLetterRepository->findNewsLetterPaginatorQuery();
    }
    #endregion

    /**
     * @param string      $param
     * @param NewsLetter $newsLetter
     *
     * @return NewsLetter
     */
    public function verifierNewsLetter(string $param, NewsLetter $newsLetter): NewsLetter
    {
        if (!isset($param) or $param != 'NewsLetters'){
            throw new NotFoundHttpException("Ce Parametre n'existe pas !");
        }

        if (!($newsLetter instanceof NewsLetter)){
            throw new NotFoundHttpException("Nous ne sommes pas en présence d'une Instance utilisateur!");
        }

        return $newsLetter;
    }


    #region Recuperer Toutes les NewsLetter
    /**
     * Retourne tous les NewsLetter
     *
     * @return array
     */
    public function allNewsLetter(): array
    {
        if (!$this->Exists()){
            throw new NotFoundHttpException("Ces NewsLetter n'existent pas !");
        }
        return $this->newsLetterRepository->findAll();
    }
    #endregion NewsLetter

    #region NewsLetter Initialisation
    /**
     *
     * @return NewsLetter
     */
    public function initialiserNewsLetter(): NewsLetter
    {

        return $newsLetter = (new NewsLetter())
            ->setNom('')
            ->setPrenom('')
            ->setMail('')
            ->setCreateAt(new DateTime('now'))
            ->setAfficher(false)
            ;
    }
    #endregion

    #region NewsLetter Initialisation
    /**
     * Ajouter une NewsLetter
     *
     * @param NewsLetter $newsLetter
     *
     * @return NewsLetter
     */
    public function addNewsLetter(NewsLetter $newsLetter): NewsLetter
    {

        if (!isset($newsLetter) or $this->exist($newsLetter->getId())){
            throw new NotFoundHttpException("Ce NewsLetter n'existe pas !");
        }

        try {

            $this->em->persist($newsLetter);
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de la sauvegarde de cette NewsLetter" . $exception->getMessage());
        }
     return $newsLetter;
    }
    #endregion

    #region Edit NewsLetter
    /**
     * Edit une NewsLetter
     *
     * @param NewsLetter $newsLetter
     *
     * @return NewsLetter
     */
    public function editNewsLetter(NewsLetter $newsLetter): NewsLetter
    {
        if (!isset($newsLetter)){
            throw new NotFoundHttpException("Cet NewsLetter n'existe pas !");
        }

        try {
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de l'enregistrement de cette NewsLetter !" . $exception->getMessage());
        }
        return $newsLetter;
    }
    #endregion Edit

    #region Supprimer une NewsLetter
    /**
     * @param $id
     *
     * @return NewsLetter
     */
    public function deleteNewsLetter($id): NewsLetter
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Ce NewsLetter n'existe pas !");
        }

        try {
            $newsLetter = $this->newsLetterRepository->find($id);
            if ($newsLetter->getId() != 1) {
                $this->em->remove($newsLetter);
                $this->em->flush();
            }else{
                throw new NotFoundHttpException("Vous ne devez pas supprimer ce NewsLetter !");
            }
        } catch (Exception $exception) {
            throw new NotFoundHttpException("Pour Information !" . $exception->getMessage());
        }

        return $newsLetter;
    }
    #endregion
}
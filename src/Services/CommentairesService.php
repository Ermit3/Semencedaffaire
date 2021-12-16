<?php

namespace App\Services;

use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints as Assert;

class CommentairesService
{

    /**
     * @var CommentaireRepository
     */
    private $commentaireRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Security
     */
    private $security;

    public function __construct(
        CommentaireRepository $commentaireRepository,
        EntityManagerInterface $em,
        Security $security
    ){
        $this->commentaireRepository = $commentaireRepository;
        $this->em = $em;
        $this->security = $security;
    }

    #region Commentaire Existe : Bool
    /**
     * Vérifie l'Existence d'une Commentaire via Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->commentaireRepository->findBy(['id'=>$id]);
    }
    #endregion Commentaire Existe

    #region Commentaire Existent : Bool
    /**
     * Vérifie que le tableau Commentaire ne soit pas vide
     *
     * @return bool
     */
    public function Exists(): bool
    {
        return [] != $this->commentaireRepository->findAll();
    }
    #endregion Commentaire Existent

    #region Recuperer une Commentaire
    /**
     * Retourne une Commentaire
     *
     * @param int $id
     *
     * @return Commentaire
     */
    public function recupeCommentaire(int $id): Commentaire
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Ce Commentaire n'existe pas !");
        }
        return $this->commentaireRepository->findOneBy(['id'=>$id]);
    }
    #endregion Commentaire Existe : Bool

    #region pagination
    /**
     * Pour la pagination des Commentaire
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->commentaireRepository->findCommPaginatorQuery();
    }
    #endregion

    /**
     * @param string      $param
     * @param Commentaire $commentaire
     *
     * @return Commentaire
     */
    public function verifierUnCommentaire(string $param, Commentaire $commentaire): Commentaire
    {
        if (!isset($param) or $param != 'Commentaires'){
            throw new NotFoundHttpException("Ce Parametre n'existe pas !");
        }

        if (!($commentaire instanceof Commentaire)){
            throw new NotFoundHttpException("Nous ne sommes pas en présence d'une Instance utilisateur!");
        }

        return $commentaire;
    }


    #region Recuperer Toutes les Commentaires
    /**
     * Retourne tous les Commentaire
     *
     * @return array
     */
    public function allCommentaire(): array
    {
        if (!$this->Exists()){
            throw new NotFoundHttpException("Ces Commentaire n'existent pas !");
        }
        return $this->commentaireRepository->findAll();
    }
    #endregion Commentaire

    #region Commentaire Initialisation
    /**
     *
     * @return Commentaire
     */
    public function initialiserCommentaire(): Commentaire
    {

        return $commentaire = (new Commentaire())
            ->setNom('')
            ->setPrenom('')
            ->setSujet('')
            ->setMail('')
            ->setText('')
            ->setCreateAt(new DateTime('now'))
            ->setAfficher(false)
            ;
    }
    #endregion

    #region Commentaire Initialisation
    /**
     * Ajouter une Commentaire
     *
     * @param Commentaire $commentaire
     *
     * @return Commentaire
     */
    public function addCommentaire(Commentaire $commentaire): Commentaire
    {

        if (!isset($commentaire) or $this->exist($commentaire->getId())){
            throw new NotFoundHttpException("Ce Commentaire n'existe pas !");
        }

        try {

            $this->em->persist($commentaire);
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de la sauvegarde de ce Commentaire" . $exception->getMessage());
        }
     return $commentaire;
    }
    #endregion

    #region Edit Commentaire
    /**
     * Edit une Commentaire
     *
     * @param Commentaire $commentaire
     *
     * @return Commentaire
     */
    public function editCommentaire(Commentaire $commentaire): Commentaire
    {
        if (!isset($commentaire)){
            throw new NotFoundHttpException("Cet Commentaire n'existe pas !");
        }

        try {
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de l'enregistrement de ce Commentaire !" . $exception->getMessage());
        }
        return $commentaire;
    }
    #endregion Edit

    #region Supprimer une Commentaire
    /**
     * @param $id
     *
     * @return Commentaire
     */
    public function deleteCommentaire($id): Commentaire
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Ce Commentaire n'existe pas !");
        }

        try {
            $commentaire = $this->commentaireRepository->find($id);
            if ($commentaire->getId() != 1) {
                $this->em->remove($commentaire);
                $this->em->flush();
            }else{
                throw new NotFoundHttpException("Vous ne devez pas supprimer ce Commentaire !");
            }
        } catch (Exception $exception) {
            throw new NotFoundHttpException("Pour Information !" . $exception->getMessage());
        }

        return $commentaire;
    }
    #endregion
}
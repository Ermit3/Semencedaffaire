<?php

namespace App\Services;

use App\Entity\Couleur;
use App\Repository\CouleurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints as Assert;

class CouleurService
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
     * @var CouleurRepository
     */
    private $couleurRepository;

    public function __construct(
        CouleurRepository $couleurRepository,
        EntityManagerInterface $em,
        Security $security
    ){

        $this->em = $em;
        $this->security = $security;
        $this->couleurRepository = $couleurRepository;
    }

    #region Couleur Existe : Bool
    /**
     * Vérifie l'Existence d'une Couleur via Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->couleurRepository->findBy(['id'=>$id]);
    }
    #endregion Couleur Existe

    #region Couleur Existent : Bool
    /**
     * Vérifie que le tableau Couleur ne soit pas vide
     *
     * @return bool
     */
    public function Exists(): bool
    {
        return [] != $this->couleurRepository->findAll();
    }
    #endregion Couleur Existent

    #region Recuperer une Couleur
    /**
     * Retourne une Couleur
     *
     * @param $id
     *
     * @return Couleur
     */
    public function recupeCouleur($id): Couleur
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cette Couleur n'existe pas !");
        }
        return $this->couleurRepository->findOneBy(['id'=>$id]);
    }
    #endregion Couleur Existe : Bool

    #region pagination
    /**
     * Pour la pagination des Couleur
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->couleurRepository->findCouleurPaginatorQuery();
    }
    #endregion

    /**
     * @param string      $param
     * @param Couleur $couleur
     *
     * @return Couleur
     */
    public function verifierUnCouleur(string $param, Couleur $couleur): Couleur
    {
        if (!isset($param) or $param != 'Couleurs'){
            throw new NotFoundHttpException("Ce Parametre n'existe pas !");
        }

        if (!($couleur instanceof Couleur)){
            throw new NotFoundHttpException("Nous ne sommes pas en présence d'une Instance utilisateur!");
        }

        return $couleur;
    }


    #region Recuperer Toutes les Couleur
    /**
     * Retourne tous les Couleur
     *
     * @return array
     */
    public function allCouleurs(): array
    {
        if (!$this->Exists()){
            throw new NotFoundHttpException("Ces Couleur n'existent pas !");
        }
        return $this->couleurRepository->findAll();
    }
    #endregion Couleur

    #region Couleur Initialisation
    /**
     *
     * @return Couleur
     */
    public function initialiserCouleurs(): Couleur
    {
        return $couleur = (new Couleur())
            ->setNom('')
            ->setDegrade('')
            ->setAfficher(0)
            ;
    }
    #endregion

    #region Couleur Initialisation
    /**
     * Ajouter une Couleur
     *
     * @param Couleur $couleur
     *
     * @return Couleur
     */
    public function addCouleur(Couleur $couleur): Couleur
    {

        if (!isset($couleur) or $this->exist($couleur->getId())){
            throw new NotFoundHttpException("Cet Couleur n'existe pas !");
        }

        try {

            $this->em->persist($couleur);
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de la sauvegarde de cet Couleur" . $exception->getMessage());
        }
     return $couleur;
    }
    #endregion

    #region Edit Couleur
    /**
     * Edit une Couleur
     *
     * @param Couleur $couleur
     *
     * @return Couleur
     */
    public function editCouleur(Couleur $couleur): Couleur
    {
        if (!isset($couleur)){
            throw new NotFoundHttpException("Cet Couleur n'existe pas !");
        }

        try {
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de l'enregistrement de cette Couleur !" . $exception->getMessage());
        }
        return $couleur;
    }
    #endregion Edit

    #region Supprimer une Couleur
    /**
     * @param $id
     *
     * @return Couleur
     */
    public function deleteCouleur($id): Couleur
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cette Couleur n'existe pas !");
        }

        try {
            $couleur = $this->couleurRepository->find($id);
            if ($couleur->getId() != 1) {
                $this->em->remove($couleur);
                $this->em->flush();
            }else{
                throw new NotFoundHttpException("Vous ne devez pas supprimer cet Couleur !");
            }
        } catch (Exception $exception) {
            throw new NotFoundHttpException("Pour Information !" . $exception->getMessage());
        }
        return $couleur;
    }
    #endregion



    #region Couleur Initialisation
    /**
     * Ajouter une Couleur
     *
     * @param Couleur $couleur
     *
     * @return Couleur
     */
    public function effetCouleur(Couleur $couleur): Couleur
    {

        if (!$this->exist($couleur->getId())){
            throw new NotFoundHttpException("Cet Couleur n'existe pas !");
        }

        try {
            $couleur->setAfficher(1);
            $this->em->persist($couleur);
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de la sauvegarde de cet Couleur" . $exception->getMessage());
        }
        return $couleur;
    }

    /**
     * @return Couleur
     */
    public function couleur():Couleur
    {
        return $this->couleurRepository->choixCouleur();
    }
}
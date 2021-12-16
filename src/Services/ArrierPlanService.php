<?php

namespace App\Services;

use App\Entity\ArrierePlan;
use App\Repository\ArrierePlanRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ArrierPlanService
 *
 * Fournisseur de services dédiés à la Gestion des ArrierPlans
 *
 * @package App\ArrierPlanService
 */
class ArrierPlanService
{
    /**
     * @var ArrierePlanRepository
     */
    private $arrierePlanRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ArrierePlanRepository $arrierePlanRepository, EntityManagerInterface $em){
        $this->arrierePlanRepository = $arrierePlanRepository;
        $this->em = $em;
    }

    #region Controle l'existence d'1 ArrPl via Nom et image
    /**
     * @param string $nom
     * @param string $image
     *
     * @return bool
     */
    public function arrierPlExisteNomImage(string $nom, string $image):bool
    {
        return [] != $this->arrierePlanRepository->findBy(['nom' =>$nom, 'filename' =>$image]);
    }
    #endregion

    #region Arriere Plan Existe : Bool
    /**
     * Vérifie l'Existence d'un ArrierePlan via Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->arrierePlanRepository->findBy(['id'=>$id]);
    }
    #endregion Arriere Plan Existe

    #region Arrieres Plans Existent : Bool
    /**
     * Vérifie que le tableau Arrieres Plans ne soit pas vide
     *
     * @return bool
     */
    public function Exists(): bool
    {
        return [] != $this->arrierePlanRepository->findAll();
    }
    #endregion Arrieres Plans Existent

    #region Recuperer un Arriere Plan
    /**
     * Retourne un Arriere Plan
     *
     * @param $id
     *
     * @return ArrierePlan
     */
    public function arrierePlan($id): ArrierePlan
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cet Arriere Plan n'existe pas !");
        }
        return $this->arrierePlanRepository->findOneBy(['id'=>$id]);
    }
    #endregion region Arriere Plan Existe : Bool

    #region Recuperer Tous les Arrieres Plans
    /**
     * Retourne tous les Arrieres Plans
     *
     * @return array
     */
    public function allArrierePlan(): array
    {
        if (!$this->Exists()){
            throw new NotFoundHttpException("Ces Arrieres Plan N'existent pas !");
        }
        return $this->arrierePlanRepository->findAll();
    }
    #endregion Tous les Arrieres Plans

    #region pagination
    /**
     * Pour la pagination des Arrieres Plans
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->arrierePlanRepository->findArrPlPaginatorQuery();
    }
    #endregion

//== Administration

    #region Ajouter un Arriere Plan : Initialiser
    /**
     * @return ArrierePlan
     */
    public function initArrierePlan(): ArrierePlan
    {
        return  (new ArrierePlan())
            ->setNom('')
            ->setFilename('Nouvelle Image en Attente')
            ->setCreateAt(new DateTime('now'))
            ->setAfficher(true)
            ->setUploadAt(new DateTime('now'));

    }
    #endregion

    #region Ajouter un Arriere Plan
    /**
     * @param ArrierePlan $arrierePlan
     *
     * @return ArrierePlan
     * @throws Exception
     */
    public function addArrierePlan(ArrierePlan $arrierePlan): ArrierePlan
    {
        if ($this->arrierPlExisteNomImage($arrierePlan->getNom(), $arrierePlan->getFilename())){
            throw new NotFoundHttpException("Cet Arriere plan existe déjà !");
        }
        try {
            $this->em->persist($arrierePlan);
            $this->em->flush();
        }catch (Exception $exception){
            throw new Exception("Erreur lors de l'enregistrement de l'Arriere Plan" . $exception->getMessage());
        }
        return $arrierePlan;
    }
    #endregion

    #region Editer un Arriere Plan
    /**
     * @param ArrierePlan $arrierePlan
     *
     * @return ArrierePlan
     * @throws Exception
     */
    public function editArrierePlan(ArrierePlan $arrierePlan): ArrierePlan
    {
        if (!$arrierePlan){
            throw new NotFoundHttpException("Cet Arriere Plan n'existe pas !");
        }
        try {
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de l'Edition de Cet Arriere Plan !" . $exception->getMessage());
        }
        return $arrierePlan;
    }
    #endregion

    #region Supprimer un Arriere Plan
    /**
     * Supprimer un Arriere Plan
     *
     * @param int $id
     *
     * @return ArrierePlan
     * @throws Exception
     */
    public function deleteArrierePlan(int $id): ArrierePlan
    {
        if (!$this->exist($id)){
            throw new Exception("Cet Arriere Plan n'existe pas !");
        }
        try {
            $arrierePlan = $this->arrierePlan($id);
            $this->em->remove($arrierePlan);
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de la Suppression de Cet Arriere Plan !" . $exception->getMessage());
        }
        return $arrierePlan;
    }
    #endregion
//== Fin Administration
}
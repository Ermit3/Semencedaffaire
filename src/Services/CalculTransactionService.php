<?php

namespace App\Services;

use App\Entity\Cotisations;
use App\Repository\CotisationsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints as Assert;

class CalculTransactionService
{

    /**
     * @var CotisationsRepository
     */
    private $cotisationsRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Security
     */
    private $security;

    public function __construct(
        CotisationsRepository $cotisationsRepository,
        EntityManagerInterface $em,
        Security $security
    ){
        $this->cotisationsRepository = $cotisationsRepository;
        $this->em = $em;
        $this->security = $security;
    }

    #region Cotisations Existe : Bool
    /**
     * Vérifie l'Existence d'une Cotisations via Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->cotisationsRepository->findBy(['id'=>$id]);
    }
    #endregion Cotisations Existe

    #region Cotisations Existent : Bool
    /**
     * Vérifie que le tableau Cotisations ne soit pas vide
     *
     * @return bool
     */
    public function Exists(): bool
    {
        return [] != $this->cotisationsRepository->findAll();
    }
    #endregion Cotisations Existent

    #region Recuperer une Cotisations
    /**
     * Retourne une Cotisations
     *
     * @param $id
     *
     * @return Cotisations
     */
    public function recupeCotisations($id): Cotisations
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cette Cotisations n'existe pas !");
        }
        return $this->cotisationsRepository->findOneBy(['id'=>$id]);
    }
    #endregion Cotisations Existe : Bool

    #region pagination
    /**
     * Pour la pagination des Cotisation
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->cotisationsRepository->findCotisPaginatorQuery();
    }
    #endregion

    /**
     * @param string      $param
     * @param Cotisations $cotisations
     *
     * @return Cotisations
     */
    public function verifierUnCotisations(string $param, Cotisations $cotisations): Cotisations
    {
        if (!isset($param) or $param != 'Cotisations'){
            throw new NotFoundHttpException("Ce Parametre n'existe pas !");
        }

        if (!($cotisations instanceof Cotisations)){
            throw new NotFoundHttpException("Nous ne sommes pas en présence d'une Instance utilisateur!");
        }

        return $cotisations;
    }


    #region Recuperer Toutes les Cotisations
    /**
     * Retourne tous les Cotisations
     *
     * @return array
     */
    public function allCotisations(): array
    {
        if (!$this->Exists()){
            throw new NotFoundHttpException("Ces Cotisations n'existent pas !");
        }
        return $this->cotisationsRepository->findAll();
    }
    #endregion Cotisations

    #region Cotisations Initialisation
    /**
     *
     * @return Cotisations
     */
    public function initialiserCotisations(): Cotisations
    {

        return $cotisations = (new Cotisations())
            ->setNom('')
            ->setPrenom('')
            ->setMontant(0.00)
            ->setCreateAt(new DateTime('now'))
            ->setAfficher(false)
            ->setSource(null)
            ;
    }
    #endregion

    #region Cotisations Initialisation
    /**
     * Ajouter une Cotisations
     *
     * @param Cotisations $cotisations
     *
     * @return Cotisations
     */
    public function addCotisations(Cotisations $cotisations): Cotisations
    {

        if (!isset($cotisations) or $this->exist($cotisations->getId())){
            throw new NotFoundHttpException("Cet cotisations n'existe pas !");
        }

        try {

            $this->em->persist($cotisations);
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de la sauvegarde de cet Cotisations" . $exception->getMessage());
        }
     return $cotisations;
    }
    #endregion

    #region Edit Cotisations
    /**
     * Edit une cotisations
     *
     * @param Cotisations $cotisations
     *
     * @return Cotisations
     */
    public function editCotisations(Cotisations $cotisations): Cotisations
    {
        if (!isset($cotisations)){
            throw new NotFoundHttpException("Cet cotisations n'existe pas !");
        }

        try {
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de l'enregistrement de cette Cotisation !" . $exception->getMessage());
        }
        return $cotisations;
    }
    #endregion Edit

    #region Supprimer une Cotisations
    /**
     * @param $id
     *
     * @return Cotisations
     */
    public function deleteCotisations($id): Cotisations
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cette Cotisations n'existe pas !");
        }

        try {
            $cotisations = $this->cotisationsRepository->find($id);
            if ($cotisations->getId() != 1) {
                $this->em->remove($cotisations);
                $this->em->flush();
            }else{
                throw new NotFoundHttpException("Vous ne devez pas supprimer cet Cotisations !");
            }
        } catch (Exception $exception) {
            throw new NotFoundHttpException("Pour Information !" . $exception->getMessage());
        }
        return $cotisations;
    }
    #endregion
}
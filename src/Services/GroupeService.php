<?php

namespace App\Services;

use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use App\Repository\SlideRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GroupeService
{

    /**
     * @var GroupeRepository
     */
    private $groupeRepository;

    public function __construct(GroupeRepository $groupeRepository){
        $this->groupeRepository = $groupeRepository;
    }

    #region Groupe Existe : Bool
    /**
     * Vérifie l'Existence d'un Groupe via Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->groupeRepository->findBy(['id'=>$id]);
    }
    #endregion Groupe Existe

    #region Groupe Existent : Bool
    /**
     * Vérifie que le tableau Groupe ne soit pas vide
     *
     * @return bool
     */
    public function Exists(): bool
    {
        return [] != $this->groupeRepository->findAll();
    }
    #endregion Groupe Existent

    #region Recuperer un Groupe
    /**
     * Retourne un Groupe
     *
     * @param $id
     *
     * @return Groupe
     */
    public function recupeGroupe($id): Groupe
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cet Groupe n'existe pas !");
        }
        return $this->groupeRepository->findOneBy(['id'=>$id]);
    }
    #endregion Groupe Existe : Bool

    #region Recuperer Tous les Groupe
    /**
     * Retourne tous les Groupe
     *
     * @return array
     */
    public function allSlides(): array
    {
        if (!$this->Exists()){
            throw new NotFoundHttpException("Ces Groupe n'existent pas !");
        }
        return $this->groupeRepository->findAll();
    }
    #endregion Groupe

    #region Créer un Groupe
    #endregion

    #region Update un Groupe
    #endregion

    #region Delete un Groupe
    #endregion
}
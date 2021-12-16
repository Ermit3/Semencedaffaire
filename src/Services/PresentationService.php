<?php

namespace App\Services;

use App\Entity\Presentation;
use App\Repository\PresentationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

class PresentationService
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
     * @var PresentationRepository
     */
    private $presentationRepository;

    public function __construct(
        PresentationRepository $presentationRepository,
        EntityManagerInterface $em, 
        Security $security
    ){
        $this->em = $em;
        $this->security = $security;
        $this->presentationRepository = $presentationRepository;
    }

    #region Presentation Existe : Bool
    /**
     * Vérifie l'Existence d'un Presentation via Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->presentationRepository->findBy(['id'=>$id]);
    }
    #endregion Presentation Existe

    #region Presentation Existent : Bool
    /**
     * Vérifie que le tableau de Presentation ne soit pas vide
     *
     * @return bool
     */
    public function Exists(): bool
    {
        return [] != $this->presentationRepository->findAll();
    }
    #endregion Presentation Existent

    #region Presentation une Presention
    /**
     * Retourne un Presentation
     *
     * @param $id
     *
     * @return Presentation
     */
    public function recupePresent($id): Presentation
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cette Presentation n'existe pas !");
        }
        return $this->presentationRepository->findOneBy(['id'=>$id]);
    }
    #endregion Presentation Existe : Bool

    #region Presentation Tous les Presentions
    /**
     * Retourne tous les Presentations
     *
     * @return array
     */
    public function allPresent(): array
    {
        if (!$this->Exists()){
            throw new NotFoundHttpException("Ces Presentation n'existent pas !");
        }
        return $this->presentationRepository->findAll();
    }
    #endregion Presentation


    #region pagination
    /**
     * Pour la pagination des Cotisation
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->presentationRepository->findPresentPaginatorQuery();
    }
    #endregion

    #region Controle l'existence d'1 Presentation via Nom et image
    /**
     * @param string $presentation
     * @param string $image
     *
     * @return bool
     */
    public function presentExistImgtext(string $image, string $presentation):bool
    {
        return [] != $this->presentationRepository->findBy(['filename' =>$image, 'texte' =>$presentation]);
    }
    #endregion
//== Administration

    #region Ajouter un Presentation : Initialiser
    /**
     * @return Presentation
     */
    public function initPresentation(): Presentation
    {
        return  (new Presentation())
            ->setFilename('Nouvelle Image en Attente')
            ->setTitre('')
            ->setSoustitre('')
            ->setTexte('')
            ->setUtilisateur(null)
            ->setAfficher(false)
            ->setAfficher(true)
            ->setCreateAt(new DateTime('now'));
    }
    #endregion

    #region Ajouter un Presentation
    /**
     * @param Presentation $presentation
     *
     * @return Presentation
     * @throws Exception
     */
    public function addPresentation(Presentation $presentation): Presentation
    {
        if ($this->presentExistImgtext($presentation->getFilename(), $presentation->getTexte())){
            throw new NotFoundHttpException("Ce Presentation existe déjà !");
        }
        try {
            $this->em->persist($presentation);
            $this->em->flush();
        }catch (Exception $exception){
            throw new Exception("Erreur lors de l'enregistrement de cette Presentation" . $exception->getMessage());
        }
        return $presentation;
    }
    #endregion

    #region Editer un Presentation
    /**
     * @param Presentation $presentation
     *
     * @return Presentation
     * @throws Exception
     */
    public function editPresentation(Presentation $presentation): Presentation
    {
        if (!$presentation){
            throw new NotFoundHttpException("Cette Presentation n'existe pas !");
        }
        try {
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de l'Edition de Cette Presentation !" . $exception->getMessage());
        }
        return $presentation;
    }
    #endregion

    #region Supprimer un Presentation
    /**
     * Supprimer un Presentation
     *
     * @param int $id
     *
     * @return Presentation
     * @throws Exception
     */
    public function deletePresentation(int $id): Presentation
    {
        if (!$this->exist($id)){
            throw new Exception("Ce Presentation n'existe pas !");
        }
        try {
            $presentation = $this->recupePresent($id);
            $this->em->remove($presentation);
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de la Suppression de Cette Presentation !" . $exception->getMessage());
        }
        return $presentation;
    }
    #endregion
//== Fin Administration
}
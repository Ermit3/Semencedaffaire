<?php

namespace App\Services;

use App\Entity\Slide;
use App\Repository\SlideRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

class SlideService
{

    /**
     * @var SlideRepository
     */
    private $slideRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Security
     */
    private $security;

    public function __construct(
        SlideRepository $slideRepository, 
        EntityManagerInterface $em, 
        Security $security
    ){
        $this->slideRepository = $slideRepository;
        $this->em = $em;
        $this->security = $security;
    }

    #region Slide Existe : Bool
    /**
     * Vérifie l'Existence d'un Slide via Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->slideRepository->findBy(['id'=>$id]);
    }
    #endregion Slide Existe

    #region Slide Existent : Bool
    /**
     * Vérifie que le tableau Slide ne soit pas vide
     *
     * @return bool
     */
    public function Exists(): bool
    {
        return [] != $this->slideRepository->findAll();
    }
    #endregion Slide Existent

    #region Recuperer un Slide
    /**
     * Retourne un Slide
     *
     * @param $id
     *
     * @return Slide
     */
    public function recupeSlide($id): Slide
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cet Slide n'existe pas !");
        }
        return $this->slideRepository->findOneBy(['id'=>$id]);
    }
    #endregion Slide Existe : Bool

    #region Recuperer Tous les Slides
    /**
     * Retourne tous les Slides
     *
     * @return array
     */
    public function allSlides(): array
    {
        if (!$this->Exists()){
            throw new NotFoundHttpException("Ces Slides n'existent pas !");
        }
        return $this->slideRepository->findAll();
    }
    #endregion Slides


    #region pagination
    /**
     * Pour la pagination des Cotisation
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->slideRepository->findSlidePaginatorQuery();
    }
    #endregion

    #region Controle l'existence d'1 ArrPl via Nom et image
    /**
     * @param string $textSlide
     * @param string $image
     *
     * @return bool
     */
    public function slideExistImgtext(string $image, string $textSlide):bool
    {
        return [] != $this->slideRepository->findBy(['filename' =>$image, 'textSlide' =>$textSlide]);
    }
    #endregion
//== Administration

    #region Ajouter un Slide : Initialiser
    /**
     * @return Slide
     */
    public function initSlide(): Slide
    {
        return  (new Slide())
            ->setTextSlide('')
            ->setSoustext('Nouvelle Image en Attente')
            ->setFilename('Nouvelle Image en Attente')
            ->setAfficher(false)
            ->setSource(null)
            ->setCreateAt(new DateTime('now'))
            ->setAfficher(true)
            ->setUploadAt(new DateTime('now'));

    }
    #endregion

    #region Ajouter un Slide
    /**
     * @param Slide $slide
     *
     * @return Slide
     * @throws Exception
     */
    public function addSlide(Slide $slide): Slide
    {
        if ($this->slideExistImgtext($slide->getFilename(), $slide->getTextSlide())){
            throw new NotFoundHttpException("Ce Slide existe déjà !");
        }
        try {
            $this->em->persist($slide);
            $this->em->flush();
        }catch (Exception $exception){
            throw new Exception("Erreur lors de l'enregistrement de ce Slide" . $exception->getMessage());
        }
        return $slide;
    }
    #endregion

    #region Editer un Slide
    /**
     * @param Slide $slide
     *
     * @return Slide
     * @throws Exception
     */
    public function editSlide(Slide $slide): Slide
    {
        if (!$slide){
            throw new NotFoundHttpException("Ce Slide n'existe pas !");
        }
        try {
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de l'Edition de Ce Slide !" . $exception->getMessage());
        }
        return $slide;
    }
    #endregion

    #region Supprimer un Slide
    /**
     * Supprimer un Slide
     *
     * @param int $id
     *
     * @return Slide
     * @throws Exception
     */
    public function deleteSlide(int $id): Slide
    {
        if (!$this->exist($id)){
            throw new Exception("Ce Slide n'existe pas !");
        }
        try {
            $slide = $this->recupeSlide($id);
            $this->em->remove($slide);
            $this->em->flush();
        } catch (Exception $exception){
            throw new Exception("Erreur lors de la Suppression de Ce Slide !" . $exception->getMessage());
        }
        return $slide;
    }
    #endregion
//== Fin Administration
}
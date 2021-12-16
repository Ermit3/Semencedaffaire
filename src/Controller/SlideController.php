<?php

namespace App\Controller;

use App\Entity\Slide;
use App\Form\SlideType;
use App\Services\ContactService;
use App\Services\CouleurService;
use App\Services\SlideService;
use App\Services\UtilisateurService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class SlideController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;
    /**
     * @var SlideService
     */
    private $slideService;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var ContactService
     */
    private $contactService;
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;

    /**
     * ArrierePlanController constructor.
     *
     * @param SlideService       $slideService
     * @param Security           $security
     * @param CouleurService     $couleurService
     * @param ContactService     $contactService
     * @param UtilisateurService $utilisateurService
     */
    public function __construct(
        SlideService        $slideService,
        Security            $security,
        CouleurService      $couleurService,
        ContactService      $contactService,
        UtilisateurService  $utilisateurService
    ){
        $this->security           = $security;
        $this->slideService       = $slideService;
        $this->couleurService     = $couleurService;
        $this->contactService     = $contactService;
        $this->utilisateurService = $utilisateurService;
    }

    /**
     * Pour Ajouter un Slide
     * @Route("/Semence/new/Slide/",name="admin.new.slide")
     *
     * @param Request $request
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function newSlide(Request $request): Response
    {
        $slideService = $this->slideService->initSlide();

        $param = 'Slides';
        $news  = 'News';
        $slid = 'Slide';

        $form = $this->createForm(SlideType::class, $slideService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->slideService->addSlide($form->getData());
                $this->addFlash('success', 'Slide Ajouté avec succèss !');
            } catch (Exception $exception){
                $this->addFlash('error', $exception->getMessage());
            }
            return $this->redirectToRoute('Essap', ['param'=>$param, 'choix'=>'Accueil']);
        }

        return $this->render(
            'Nzoe/userLa.html.twig',
            [
                'param'       => $param,
                'news'        => $news,
                'slide'       => $slid,
                'Slids'       => $this->slideService->allSlides(),
                'form'        => $form->createView(),
                'couleurs'    => $this->couleurService->couleur(),
                'emails'      => count($this->contactService->ContactUserRecu()),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
            ]
        );
    }

    /**
     * Pour editer un Slide
     *
     * @Route("/Essap/edit/Slide/{param}/{id}", name="admin.edit.slide", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param int     $id
     * @param string  $param
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function editerSlide(Request $request, int $id, string $param): Response
    {
        $slide = $this->slideService->recupeSlide($id);

        if ($param != 'Slides'){
            $this->addFlash("error","Ce Parametre n'existe pas !");
        }

        $edit       = 'Edit';
        $sli        = 'Slide';
        $form       = $this->createForm(SlideType::class, $slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->slideService->editSlide($form->getData());
                $this->addFlash('success', 'Slide Modifié avec succès !');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
            return $this->redirectToRoute('admin.edit.slide', [
                'param'       => $param,
                'id'          => $id,
                'slide'       => $sli,
                'Slids'       => $this->slideService->allSlides(),
                'couleurs'    => $this->couleurService->couleur(),
                'emails'      => count($this->contactService->ContactUserRecu()),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
            ]);
        }

        return $this->render(
            'Nzoe/userLa.html.twig',
            [
                'param'       => $param,
                'edit'        => $edit,
                'slide'       => $sli,
                'form'        => $form->createView(),
                'Slids'       => $this->slideService->allSlides(),
                'couleurs'    => $this->couleurService->couleur(),
                'emails'      => count($this->contactService->ContactUserRecu()),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
            ]
        );
    }

    /**
     * Pour supprimer un Slide
     *
     * @Route("/Semence/delete/Slide/{id}",name="admin.delete.slide", requirements={"id":"\d+"})
     *
     * @param Request     $request
     * @param Slide       $slide
     * @param             $id
     *
     * @return Response
     */
    public function deleteSlide(Request $request, Slide $slide, $id): Response
    {
        if ($this->isCsrfTokenValid('delete'. $slide->getId(), $request->get('_token'))) {
            try {
                $this->slideService->recupeSlide($id);
                $this->addFlash('success', 'Slide Supprimé avec succès!');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }
        return $this->redirectToRoute('Essap', ['param'=>'Slides', 'choix'=>'Accueil']);
    }

}
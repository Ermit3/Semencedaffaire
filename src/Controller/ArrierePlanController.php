<?php

namespace App\Controller;

use App\Entity\ArrierePlan;
use App\Form\ArrierePlanType;
use App\Services\ArrierPlanService;
use App\Services\ContactService;
use App\Services\CouleurService;
use App\Services\SlideService;
use App\Services\UtilisateurService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ArrierePlanController extends AbstractController
{

    private $arrierePlanService;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var \App\Services\ContactService
     */
    private $contactService;
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;
    /**
     * @var SlideService
     */
    private $slideService;

    /**
     * ArrierePlanController constructor.
     *
     * @param ArrierPlanService  $arrierePlanService
     * @param Security           $security
     * @param CouleurService     $couleurService
     * @param ContactService     $contactService
     * @param UtilisateurService $utilisateurService
     */
    public function __construct(
        ArrierPlanService   $arrierePlanService,
        Security            $security,
        CouleurService      $couleurService,
        ContactService      $contactService,
        UtilisateurService  $utilisateurService,
        SlideService        $slideService
    ){
        $this->arrierePlanService = $arrierePlanService;
        $this->security           = $security;
        $this->couleurService     = $couleurService;
        $this->contactService     = $contactService;
        $this->utilisateurService = $utilisateurService;
        $this->slideService = $slideService;
    }

    /**
     * Pour Ajouter un Arriere Plan
     * @Route("/Semence/new/Arriere/",name="admin.new.arrpl")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function newArrierPl(Request $request): Response
    {
        $arrierePlan = $this->arrierePlanService->initArrierePlan();
        $arrplFile   = $this->arrierePlanService->arrierePlan(1);

        $param       = 'ArrierePlan';
        $news        = 'News';

        $form = $this->createForm(ArrierePlanType::class, $arrierePlan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->arrierePlanService->addArrierePlan($form->getData());
                $this->addFlash('success', 'Arriere Plan Ajouté avec succèss !');
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
                'formul'      => $form->createView(),
                'arrpl'       => $arrplFile,
                'Slids'       => $this->slideService->allSlides(),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
                'couleurs'    => $this->couleurService->couleur(),
                'emails'      => count($this->contactService->ContactUserRecu()),
            ]
        );
    }

    /**
     * Pour editer un Arriereplan
     *
     * @Route("/Essap/edit/{param}/{id}", name="admin.edit.arrpl", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param int     $id
     * @param string  $param
     *
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function editArrierPl(Request $request, int $id, string $param): Response
    {
        $arrierPl   = $this->arrierePlanService->arrierePlan($id);
        $arrplFile  = $this->arrierePlanService->arrierePlan(1);

        if ($param != 'ArrierePlan'){
            $this->addFlash("error","Ce Parametre n'existe pas !");
        }

        $edit       = 'Edit';
        $form       = $this->createForm(ArrierePlanType::class, $arrierPl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->arrierePlanService->editArrierePlan($form->getData());
                $this->addFlash('success', 'Arriere Plan Modifié avec succès !');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
            return $this->redirectToRoute('admin.edit.arrpl', [
                'param'  =>$param,
                'id'     => $id,
                'Slids'       => $this->slideService->allSlides(),
                'emails' => count($this->contactService->ContactUserRecu()),
            ]);
        }

        return $this->render(
            'Nzoe/userLa.html.twig',
            [
                'param'       => $param,
                'edit'        => $edit,
                'form'        => $form->createView(),
                'Slids'       => $this->slideService->allSlides(),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                'arrpl'       => $arrplFile,
                'couleurs'    => $this->couleurService->couleur(),
                'emails'      => count($this->contactService->ContactUserRecu()),
            ]
        );
    }

    /**
     * Pour supprimer un Arriereplan
     * @Route("/Semence/delete/Arriere/{id}",name="admin.delete.arrpl", requirements={"id":"\d+"})
     *
     * @param Request     $request
     * @param ArrierePlan $arrierePlan
     * @param             $id
     *
     * @return Response
     */
    public function deleteArrierPl(Request $request, ArrierePlan $arrierePlan, $id): Response
    {
        if ($this->isCsrfTokenValid('delete'. $arrierePlan->getId(), $request->get('_token'))) {
            try {
                $this->arrierePlanService->deleteArrierePlan($id);
                $this->addFlash('success', 'Arriere Plan Supprimé avec succès!');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }
        return $this->redirectToRoute('Essap', ['param'=>'ArrierePlan', 'choix'=>'Accueil']);
    }

}
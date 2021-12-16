<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\PresentationType;
use App\Services\ContactService;
use App\Services\CouleurService;
use App\Services\PresentationService;
use App\Services\UtilisateurService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class RealisationController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;
    /**
     * @var PresentationService
     */
    private $presentationService;
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
     * PresentationController constructor.
     *
     * @param PresentationService $presentationService
     * @param Security            $security
     * @param CouleurService      $couleurService
     * @param ContactService      $contactService
     * @param UtilisateurService  $utilisateurService
     */
    public function __construct(
        PresentationService $presentationService,
        Security            $security,
        CouleurService      $couleurService,
        ContactService      $contactService,
        UtilisateurService  $utilisateurService
    ){
        $this->security            = $security;
        $this->presentationService = $presentationService;
        $this->couleurService      = $couleurService;
        $this->contactService      = $contactService;
        $this->utilisateurService  = $utilisateurService;
    }
//Afficher las Realisations

    /**
     * Cette methode permet d'afficher les Realisation
     *
     * @Route("Realisation/", name="accueil.real.elere")
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function afficheRealisation(): Response
    {
        return $this->render('pages/Realisations/realisation.html.twig', [
            'couleurs'    => $this->couleurService->couleur(),
            'Present'     => $this->presentationService->allPresent(),
            'emails'      => count($this->contactService->ContactUserRecu()),
            'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
        ]);
    }

    /**
     * Pour Ajouter une Presentation
     * @Route("/Semence/new/Presentation/",name="admin.new.presentation")
     *
     * @param Request $request
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function newPresentation(Request $request): Response
    {
        $article = $this->presentationService->initPresentation();

        $param = 'Presentation';
        $news  = 'News';

        $form = $this->createForm(PresentationType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->presentationService->addPresentation($form->getData());
                $this->addFlash('success', 'Presentation Ajouté avec succèss !');
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
                'form'        => $form->createView(),
                'couleurs'    => $this->couleurService->couleur(),
                'emails'      => count($this->contactService->ContactUserRecu()),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
            ]
        );
    }

    /**
     * Pour editer une Presentation
     *
     * @Route("/Essap/edit/Presentation/{param}/{id}", name="admin.edit.presentation", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param int     $id
     * @param string  $param
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function editPresentation(Request $request, int $id, string $param): Response
    {
        $presentation = $this->presentationService->recupePresent($id);

        if ($param != 'Presentation'){
            $this->addFlash("error","Ce Parametre n'existe pas !");
        }

        $edit       = 'Edit';
        $form       = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->presentationService->editPresentation($form->getData());
                $this->addFlash('success', 'Presentation Modifiée avec succès !');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
            return $this->redirectToRoute('admin.edit.presentation', [
                'param'       => $param,
                'id'          => $id,
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
                'form'        => $form->createView(),
                'couleurs'    => $this->couleurService->couleur(),
                'emails'      => count($this->contactService->ContactUserRecu()),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
            ]
        );
    }

    /**
     * Pour supprimer un Presentation
     *
     * @Route("/Semence/delete/Presentation/{id}",name="admin.delete.presentation", requirements={"id":"\d+"})
     *
     * @param Request     $request
     * @param Article     $article
     * @param             $id
     *
     * @return Response
     */
    public function deletePresentation(Request $request, Article $article, $id): Response
    {
        if ($this->isCsrfTokenValid('delete'. $article->getId(), $request->get('_token'))) {
            try {
                $this->presentationService->deletePresentation($id);
                $this->addFlash('success', 'Presentation Supprimée avec succès!');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }
        return $this->redirectToRoute('Essap', ['param'=>'Presentation', 'choix'=>'Accueil']);
    }

}
<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Cotisations;
use App\Form\CommentaireType;
use App\Form\CotisationType;
use App\Services\CommentairesService;
use App\Services\ContactService;
use App\Services\CotisationService;
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

class CommentairesController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var SlideService
     */
    private $slideService;
    /**
     * @var CommentairesService
     */
    private $commentairesService;
    /**
     * @var ContactService
     */
    private $contactService;
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;

    public function __construct(
        CommentairesService $commentairesService,
        Security            $security,
        CouleurService      $couleurService,
        SlideService        $slideService,
        ContactService      $contactService,
        UtilisateurService  $utilisateurService
    ){
        $this->security            = $security;
        $this->couleurService      = $couleurService;
        $this->slideService        = $slideService;
        $this->commentairesService = $commentairesService;
        $this->contactService      = $contactService;
        $this->utilisateurService = $utilisateurService;
    }

    #region Ajouter une Commentaire

    /**
     * Ajouter un Commentaire
     *
     * @Route("Essap/Commentaires/add/", name="admin.add.commentaire")
     *
     * @param Request $request
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function ajouterCommentaire(Request $request): Response
    {
        $commentaire = $this->commentairesService->initialiserCommentaire();
        $form        = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commentairesService->addCommentaire($form->getData());
                $this->addFlash('success','Commentaire Ajouté avec succès !');
            } catch (Exception $exception){
                $this->addFlash('error','Erreur lors de la Modification de ce Commentaire !' . $exception->getMessage());
            }
            return $this->redirectToRoute('Essap',['param'=>'Commentaires', 'choix'=>'Accueil']);
        }

             $param        = 'Commentaires';
             $choix        = 'Accueil';

             $news         = 'News';
             return $this->render('Nzoe/userLa.html.twig',[
            'form'         => $form->createView(),
            'news'         => $news,
            'Commentaire'  => $commentaire,
            'param'        => $param,
            'Slids'        => $this->slideService->allSlides(),
            'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
            'choix'        => $choix,
            'couleurs'     => $this->couleurService->couleur(),
            'emails'       => count($this->contactService->ContactUserRecu()),
        ]);
    }
    #endregion /Ajouter Commentaires

    #region Editer une Commentaires
    /**
     * Modifier une Commentaires
     *
     * @Route("Essap/Commentaire/{param}/{id}", name="admin.edit.commentaire", requirements={"id":"\d+"})
     *
     * @param Request     $request
     * @param string      $param
     * @param Commentaire $commentaire
     * @param int         $id
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function modifierComm(Request $request, string $param, Commentaire $commentaire, int $id): Response
    {
        try {
            $this->commentairesService->verifierUnCommentaire($param, $commentaire);
        } catch (Exception $exception){
            $this->addFlash('error',$exception->getMessage());
        }

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->commentairesService->editCommentaire($form->getData());
                $this->addFlash('success','Commentaire modifiée avec succès !');
            } catch (Exception $exception){
                $this->addFlash('error','Erreur lors de la Modification de cette Commentaire !' . $exception->getMessage());
            }
        }

        if ($this->security->getUser()->getRoles() == ['ROLE_USER']) {
            $edit = 'Edit';
            return $this->render('Nzoe/userFo.html.twig',[
                'form'         => $form->createView(),
                'edit'         => $edit,
                'param'        => $param,
                'choix'        => 'Accueil',
                'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                'Commentaires' => $commentaire,
                'couleurs'     => $this->couleurService->couleur(),
                'Slids'        => $this->slideService->allSlides(),
                'emails'       => count($this->contactService->ContactUserRecu()),

            ]);

        }

        if ($this->security->getUser()->getRoles() == ['ROLE_ADMIN']) {
            $edit = 'Edit';
            return $this->render(
                'Nzoe/userBet.html.twig',
                [
                    'form'         => $form->createView(),
                    'edit'         => $edit,
                    'param'        => $param,
                    'Commentaires' => $commentaire,
                    'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                    'couleurs'     => $this->couleurService->couleur(),
                    'Slids'        => $this->slideService->allSlides(),
                    'emails'       => count($this->contactService->ContactUserRecu()),
                ]
            );
        } else {

            $edit = 'Edit';
            return $this->render(
                'Nzoe/userLa.html.twig',
                [
                    'form'         => $form->createView(),
                    'edit'         => $edit,
                    'param'        => $param,
                    'Commentaires' => $commentaire,
                    'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                    'couleurs'     => $this->couleurService->couleur(),
                    'Slids'        => $this->slideService->allSlides(),
                    'emails'       => count($this->contactService->ContactUserRecu()),
                ]);
        }

    }
    #endregion /Editer Commentaire

    #region Supprimer une Commentaire
    /**
     * Supprimer une Commentaire
     *
     * @Route("Essap/Modifier/Commentaires/{id}", name="admin.delete.commentaire", requirements={"id":"\d+"})
     *
     * @param Commentaire $commentaire
     * @param Request     $request
     *
     * @return Response
     */
    public function suppCotis(Commentaire $commentaire, Request $request): Response
    {
        //  $this->denyAccessUnlessGranted(AkobisoftVoter::DELETE, $commentaire, "Commentaire a tenté d'accéder à une page sans ROLE_SUPER_ADMIN ");

        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->get('_token'))) {
            try {
                $this->commentairesService->deleteCommentaire($commentaire->getId());
                $this->addFlash('success', 'Commentaires Supprimée avec succès !');
            } catch (Exception $exception){
                $this->addFlash('info', $exception->getMessage());
            }
        }
        return $this->redirectToRoute('Essap',['param'=>'Commentaires', 'choix'=>'Accueil']);
    }
    #endregion /Supprimer Commentaires
}

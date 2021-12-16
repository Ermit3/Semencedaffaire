<?php

namespace App\Controller;

use App\Entity\NewsLetter;
use App\Form\CommentaireType;
use App\Form\NewsletterType;
use App\Services\ContactService;
use App\Services\CouleurService;
use App\Services\NewsLetterService;
use App\Services\SlideService;
use App\Services\UtilisateurService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class NewsLetterController extends AbstractController
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
     * @var NewsLetterService
     */
    private $newsLetterService;
    /**
     * @var ContactService
     */
    private $contactService;
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;

    public function __construct(
        NewsLetterService   $newsLetterService,
        Security            $security,
        CouleurService      $couleurService,
        SlideService        $slideService,
        ContactService      $contactService,
        UtilisateurService  $utilisateurService
    ){
        $this->security            = $security;
        $this->couleurService      = $couleurService;
        $this->slideService        = $slideService;
        $this->newsLetterService   = $newsLetterService;
        $this->contactService = $contactService;
        $this->utilisateurService = $utilisateurService;
    }

    #region Ajouter une NewsLetter

    /**
     * Ajouter un NewsLetter
     *
     * @Route("Essap/NewsLetters/add/", name="admin.add.newsletter")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function ajouterNewsLetter(Request $request): Response
    {
        $newsLetters = $this->newsLetterService->initialiserNewsLetter();
        $form        = $this->createForm(NewsletterType::class, $newsLetters);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->newsLetterService->addNewsLetter($form->getData());
                $this->addFlash('success','NewsLetter Ajouté avec succès !');
            } catch (Exception $exception){
                $this->addFlash('error','Erreur lors de la Modification de ce NewsLetter !' . $exception->getMessage());
            }
            return $this->redirectToRoute('Essap',['param'=>'NewsLetters', 'choix'=>'Accueil']);
        }

             $param        = 'NewsLetters';
             $news         = 'News';
             return $this->render('Nzoe/userLa.html.twig',[
            'form'         => $form->createView(),
            'news'         => $news,
            'NewsLetters'  => $newsLetters,
            'Slids'       => $this->slideService->allSlides(),
            'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
            'param'        => $param,
            'couleurs'     => $this->couleurService->couleur(),
            'emails'       => count($this->contactService->ContactUserRecu()),
        ]);
    }
    #endregion /Ajouter NewsLetters

    #region Editer une NewsLetters
    /**
     * Modifier une NewsLetters
     *
     * @Route("Essap/NewsLetters/{param}/{id}", name="admin.edit.newsletter", requirements={"id":"\d+"})
     *
     * @param Request    $request
     * @param string     $param
     * @param NewsLetter $newsletters
     * @param int        $id
     *
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function modifierComm(Request $request, string $param, NewsLetter $newsletters, int $id): Response
    {
        try {
            $this->newsLetterService->verifierNewsLetter($param, $newsletters);
        } catch (Exception $exception){
            $this->addFlash('error',$exception->getMessage());
        }

        $form = $this->createForm(NewsletterType::class, $newsletters);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->newsLetterService->editNewsLetter($form->getData());
                $this->addFlash('success','NewsLetter modifiée avec succès !');
            } catch (Exception $exception){
                $this->addFlash('error','Erreur lors de la Modification de cette NewsLetter !' . $exception->getMessage());
            }
        }

        if ($this->security->getUser()->getRoles() == ['ROLE_USER']) {
            $edit = 'Edit';
            return $this->render('Nzoe/userFo.html.twig',[
                'form'         => $form->createView(),
                'edit'         => $edit,
                'param'        => $param,
                'choix'        => 'Accueil',
                'NewsLetters'  => $newsletters,
                'couleurs'     => $this->couleurService->couleur(),
                'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
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
                    'NewsLetters'  => $newsletters,
                    'couleurs'     => $this->couleurService->couleur(),
                    'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                    'Slids'        => $this->slideService->allSlides(),
                    'emails'       => count($this->contactService->ContactUserRecu()),
                ]
            );
        }

        if ($this->security->getUser()->getRoles() == ['ROLE_SUPER_ADMIN']) {
            $edit = 'Edit';
            return $this->render(
                'Nzoe/userLa.html.twig',
                [
                    'form'         => $form->createView(),
                    'edit'         => $edit,
                    'param'        => $param,
                    'NewsLetters'  => $newsletters,
                    'couleurs'     => $this->couleurService->couleur(),
                    'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                    'Slids'        => $this->slideService->allSlides(),
                    'emails'       => count($this->contactService->ContactUserRecu()),
                ]
            );
        }
    }
    #endregion /Editer NewsLetter

    #region Supprimer une NewsLetter
    /**
     * Supprimer une NewsLetter
     *
     * @Route("Essap/Modifier/NewsLetters/{id}", name="admin.delete.newsletter", requirements={"id":"\d+"})
     *
     * @param NewsLetter $newsletter
     * @param Request     $request
     *
     * @return Response
     */
    public function suppCotis(NewsLetter $newsletter, Request $request): Response
    {
        //  $this->denyAccessUnlessGranted(AkobisoftVoter::DELETE, $newsletter, "Newsletter a tenté d'accéder à une page sans ROLE_SUPER_ADMIN ");

        if ($this->isCsrfTokenValid('delete'.$newsletter->getId(), $request->get('_token'))) {
            try {
                $this->newsLetterService->deleteNewsLetter($newsletter->getId());
                $this->addFlash('success', 'NewsLetter Supprimée avec succès !');
            } catch (Exception $exception){
                $this->addFlash('info', $exception->getMessage());
            }
        }
        return $this->redirectToRoute('Essap',['param'=>'NewsLetters', 'choix'=>'Accueil']);
    }
    #endregion /Supprimer NewsLetter
}

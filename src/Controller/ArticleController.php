<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\ContactType;
use App\Services\ArticleService;
use App\Services\ContactService;
use App\Services\CouleurService;
use App\Services\PresentationService;
use App\Services\SlideService;
use App\Services\UtilisateurService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ArticleController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;
    /**
     * @var ArticleService
     */
    private $articleService;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var PresentationService
     */
    private $presentationService;
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
     * @param ArticleService      $articleService
     * @param Security            $security
     * @param CouleurService      $couleurService
     * @param SessionInterface    $session
     * @param PresentationService $presentationService
     * @param ContactService      $contactService
     * @param UtilisateurService  $utilisateurService
     * @param SlideService        $slideService
     */
    public function __construct(
        ArticleService      $articleService,
        Security            $security,
        CouleurService      $couleurService,
        SessionInterface    $session,
        PresentationService $presentationService,
        ContactService      $contactService,
        UtilisateurService  $utilisateurService,
        SlideService        $slideService
    ){
        $this->security            = $security;
        $this->articleService      = $articleService;
        $this->couleurService      = $couleurService;
        $this->session             = $session;
        $this->presentationService = $presentationService;
        $this->contactService      = $contactService;
        $this->utilisateurService  = $utilisateurService;
        $this->slideService = $slideService;
    }

//Afficher les Articles

    /**
     * Cette methode permet d'afficher les Articles
     *
     * @Route("Article/{id}", name="accueil.art.elere", requirements={"id":"\d+"})
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function afficheArticle(Request $request, int $id): Response
    {
        $article = $this->articleService->article($id??$request->get('id'));
        $contact = $this->contactService->initialiserContact();
        $produits = 'Article';
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()){
            $form->getData();

            try {
                $this->contactService->addContact($form->getData());
                $this->addFlash('success','Message enregistré avec succès !');
            }catch (Exception $e){
                $this->addFlash('error',$e->getMessage());
            }
            return $this->redirectToRoute('accueil');
        }

       return $this->render('pages/Articles/article.html.twig', [
           'couleurs'    => $this->couleurService->couleur(),
           'Present'     => $this->presentationService->allPresent(),
           'article'     => $article,
           'produits'    => $produits,
           'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
           'form'        => $form->createView(),
           'emails'      => count($this->contactService->ContactUserRecu()),
       ]);
    }
// Administration des Articles

    /**
     * Pour Ajouter un Article
     * @Route("/Semence/new/Article/",name="admin.new.article")
     *
     * @param Request $request
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function newArticle(Request $request): Response
    {
        $article = $this->articleService->initArticle();

        $param    = 'Article';
        $news     = 'News';
        $produits = 'Article';

        $form  = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->articleService->addArticle($form->getData());
                $this->addFlash('success', 'Article Ajouté avec succèss !');
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
                'produits'    => $produits,
                'Slids'       => $this->slideService->allSlides(),
                'formul'      => $form->createView(),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                'couleurs'    => $this->couleurService->couleur(),
                'emails'      => count($this->contactService->ContactUserRecu()),
            ]
        );
    }

    /**
     * Pour editer un Arriereplan
     *
     * @Route("/Essap/edit/Article/{param}/{id}", name="admin.edit.article", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param int     $id
     * @param string  $param
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function editArticle(Request $request, int $id, string $param): Response
    {
        $article    = $this->articleService->article($id);

        if ($param != 'Article'){
            $this->addFlash("error","Ce Parametre n'existe pas !");
        }

        $edit       = 'Edit';
        $produits   = 'Article';
        $form       = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $this->articleService->editArticle($form->getData());
                $this->addFlash('success', 'Article Modifié avec succès !');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
            return $this->redirectToRoute('admin.edit.article', [
                'param'       => $param,
                'id'          => $id,
                'article'     => $article,
                'Slids'       => $this->slideService->allSlides(),
                'produits'    => $produits,
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                'couleurs'    => $this->couleurService->couleur(),
                'emails'      => count($this->contactService->ContactUserRecu()),
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
                'couleurs'    => $this->couleurService->couleur(),
                'emails'      => count($this->contactService->ContactUserRecu()),
                'produits'    => $produits,
            ]
        );
    }

    /**
     * Pour supprimer un Article
     *
     * @Route("/Semence/delete/Article/{id}",name="admin.delete.article", requirements={"id":"\d+"})
     *
     * @param Request     $request
     * @param Article     $article
     * @param             $id
     *
     * @return Response
     */
    public function deleteArticle(Request $request, Article $article, $id): Response
    {
        if ($this->isCsrfTokenValid('delete'. $article->getId(), $request->get('_token'))) {
            try {
                $this->articleService->deleteArticle($id);
                $this->addFlash('success', 'Article Supprimé avec succès!');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }
        return $this->redirectToRoute('Essap', ['param'=>'Article', 'choix'=>'Accueil']);
    }

}
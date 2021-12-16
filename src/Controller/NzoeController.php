<?php

namespace App\Controller;

use App\Services\ArrierPlanService;
use App\Services\ArticleService;
use App\Services\CalculeArborescenceService;
use App\Services\CalculeGainsService;
use App\Services\CalculTransactionService;
use App\Services\CategorieService;
use App\Services\CommentairesService;
use App\Services\ContactService;
use App\Services\CotisationService;
use App\Services\CouleurService;
use App\Services\LigneCommandesService;
use App\Services\NewsLetterService;
use App\Services\Panier\PanierService;
use App\Services\PresentationService;
use App\Services\ProduitService;
use App\Services\SlideService;
use App\Services\UtilisateurService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class NzoeController extends AbstractController
{
    /**
     * @Security("has_role('IS_AUTHENTICATED_ANONYMOUSLY')")
     */

    /**
     * @var Security
     */
    private $security;
    /**
     * @var ArrierPlanService
     */
    private $arrierPlanService;
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;
    /**
     * @var CotisationService
     */
    private $cotisationService;
    /**
     * @var SlideService
     */
    private $slideService;
    /**
     * @var ArticleService
     */
    private $articleService;
    /**
     * @var PresentationService
     */
    private $presentationService;
    /**
     * @var ProduitService
     */
    private $produitService;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var CalculeGainsService
     */
    private $calculeGainsService;
    /**
     * @var CalculeArborescenceService
     */
    private $calculeArborescenceService;
    /**
     * @var CalculTransactionService
     */
    private $calculTransactionService;
    /**
     * @var CommentairesService
     */
    private $commentairesService;
    /**
     * @var NewsLetterService
     */
    private $newsLetterService;
    /**
     * @var ContactService
     */
    private $contactService;
    /**
     * @var CategorieService
     */
    private $categorieService;
    /**
     * @var PanierService
     */
    private $panierService;
    /**
     * @var LigneCommandesService
     */
    private $ligneCommandesService;

    public function __construct(
        Security                   $security,
        ArrierPlanService          $arrierPlanService,
        UtilisateurService         $utilisateurService,
        CotisationService          $cotisationService,
        SlideService               $slideService,
        ArticleService             $articleService,
        PresentationService        $presentationService,
        ProduitService             $produitService,
        CouleurService             $couleurService,
        CalculeGainsService        $calculeGainsService,
        CalculeArborescenceService $calculeArborescenceService,
        CalculTransactionService   $calculTransactionService,
        CommentairesService        $commentairesService,
        NewsLetterService          $newsLetterService,
        ContactService             $contactService,
        CategorieService           $categorieService,
        PanierService              $panierService,
        LigneCommandesService      $ligneCommandesService
    ){
        $this->security            = $security;
        $this->arrierPlanService   = $arrierPlanService;
        $this->utilisateurService  = $utilisateurService;
        $this->cotisationService   = $cotisationService;
        $this->slideService        = $slideService;
        $this->articleService      = $articleService;
        $this->presentationService = $presentationService;
        $this->produitService      = $produitService;
        $this->couleurService      = $couleurService;
        $this->calculeGainsService = $calculeGainsService;
        $this->calculeArborescenceService = $calculeArborescenceService;
        $this->calculTransactionService   = $calculTransactionService;
        $this->commentairesService        = $commentairesService;
        $this->newsLetterService          = $newsLetterService;
        $this->contactService             = $contactService;
        $this->categorieService           = $categorieService;
        $this->panierService              = $panierService;
        $this->ligneCommandesService      = $ligneCommandesService;
    }


    /**
     * @Route("/Essap/{param}/{choix}", name="Essap")
     *
     * @param PaginatorInterface $paginator
     * @param string             $param
     * @param string             $choix
     * @param Request            $request
     * @param SessionInterface   $session
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function index(PaginatorInterface $paginator, string $param = 'Accueil', string $choix = 'Accueil', Request $request, SessionInterface $session): Response
    {

        $commandes    = $this->ligneCommandesService->ligneComandeByUser($this->getUser()->getId());

        if ([] == $this->ligneCommandesService->allLigneComande()) {
            $this->addFlash('error', "Vous n'avez pas de commandes");
        }else{
            $allcommandes = $this->ligneCommandesService->allLigneComande();
        }

        $arrPlPagine = $paginator->paginate(
            $this->arrierPlanService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $utilPagine = $paginator->paginate(
            $this->utilisateurService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $cotisPagine = $paginator->paginate(
            $this->cotisationService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $slidePagine = $paginator->paginate(
            $this->slideService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $articlPagine = $paginator->paginate(
            $this->articleService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $presentPagine = $paginator->paginate(
            $this->presentationService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $produitPagine = $paginator->paginate(
            $this->produitService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $couleurPagine = $paginator->paginate(
            $this->couleurService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $commentPagine = $paginator->paginate(
            $this->commentairesService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $newletPagine = $paginator->paginate(
            $this->newsLetterService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $contactPagine = $paginator->paginate(
            $this->contactService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $categoriePagine = $paginator->paginate(
            $this->categorieService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $ligncomPagine = $paginator->paginate(
            $this->ligneCommandesService->pagination(),
            $request->query->getInt('page', 1),
            5
        );

        $detailPanier = $this->panierService->getDetailItem();
        $total        = $this->panierService->getTotal();
        $quantite     = $this->panierService->getQuantite();

        try {

            $EnfantGauch = $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getLft():'');
            $EnfantDroit = $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getLvl():'');

            $EnfantGauch1 = $this->utilisateurService->recupeUtilisateure($EnfantGauch?$EnfantGauch->getLft():'');
            $EnfantGauch2 = $this->utilisateurService->recupeUtilisateure($EnfantGauch?$EnfantGauch->getLvl():'');

            $EnfantDroit1 = $this->utilisateurService->recupeUtilisateure($EnfantDroit?$EnfantDroit->getLft():'');
            $EnfantDroit2 = $this->utilisateurService->recupeUtilisateure($EnfantDroit?$EnfantDroit->getLvl():'');

        }catch (Exception $exception){
            $this->addFlash('error',$exception->getMessage());
        }

        if ($this->security->getUser()->getRoles() === ['ROLE_USER']){
            $arrPl = $this->arrierPlanService->arrierePlan(1)->getFilename();
            return $this->render('Nzoe/userFo.html.twig', [
                 'Slids'        => $this->slideService->allSlides(),
                 'arrPl'        => $arrPl,
                 'param'        => $param,
                 'couleurs'     => $this->couleurService->couleur(),
                 'choix'        => $choix,
                 'commPagines'  => $commentPagine,
                 'prodPagines'  => $produitPagine,
                 'items'        => $detailPanier,
                 'totat'        => $total,
                 'quantite'     => $quantite,
                 'newlePagine'  => $newletPagine,
                 'ligncomPagine' => $ligncomPagine,
                 'commandes'    => $commandes,
                 'categories'   => $this->categorieService->allCategorie(),

                 'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),

                 'EnfantGauch' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getLft()??''),
                 'EnfantDroit' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getLvl()??''),

                 'EnfantGauch1' => $this->utilisateurService->recupeUtilisateure(isset($EnfantGauch)?$EnfantGauch->getLft():''),
                 'EnfantGauch2' => $this->utilisateurService->recupeUtilisateure($EnfantGauch?$EnfantGauch->getLvl():''),

                 'EnfantDroit1' => $this->utilisateurService->recupeUtilisateure($EnfantDroit?$EnfantDroit->getLft():''),
                 'EnfantDroit2' => $this->utilisateurService->recupeUtilisateure($EnfantDroit?$EnfantDroit->getLvl():''),

                 'EnfantGauch11' => $this->utilisateurService->recupeUtilisateure($EnfantGauch1?$EnfantGauch1->getLft():''),
                 'EnfantGauch12' => $this->utilisateurService->recupeUtilisateure($EnfantGauch1?$EnfantGauch1->getLvl():''),

                 'EnfantGauch21' => $this->utilisateurService->recupeUtilisateure($EnfantGauch2?$EnfantGauch2->getLft():''),
                 'EnfantGauch22' => $this->utilisateurService->recupeUtilisateure($EnfantGauch2?$EnfantGauch2->getLvl():''),

                 'EnfantDroit11' => $this->utilisateurService->recupeUtilisateure($EnfantDroit1?$EnfantDroit1->getLft():''),
                 'EnfantDroit12' => $this->utilisateurService->recupeUtilisateure($EnfantDroit1?$EnfantDroit1->getLvl():''),

                 'EnfantDroit21' => $this->utilisateurService->recupeUtilisateure($EnfantDroit2?$EnfantDroit2->getLft():''),
                 'EnfantDroit22' => $this->utilisateurService->recupeUtilisateure($EnfantDroit2?$EnfantDroit2->getLvl():''),

                 'Cotisations'  => $this->cotisationService->recupeCotisations($this->security->getUser()->getId()),
                 'last'         => $this->utilisateurService->lastUtilisateur(),
                 'emails'       => count($this->contactService->ContactUserRecu()),
            ]);

        }

        if ($this->security->getUser()->getRoles() === ['ROLE_ADMIN']){
            $arrPl = $this->arrierPlanService->arrierePlan(2)->getFilename();

            return $this->render('Nzoe/userBet.html.twig', [
                  'Slids'       => $this->slideService->allSlides(),
                  'arrPl'       => $arrPl,
                  'param'       => $param,
                  'couleurs'    => $this->couleurService->couleur(),
                  'choix'       => $choix,
                  'commPagines' => $commentPagine,
                  'prodPagines' => $produitPagine,
                  'newlePagine' => $newletPagine,
                  'pagineUtill' => $utilPagine,
                  'pagineCotis' => $cotisPagine,
                  'artiPagines' => $articlPagine,
                  'items'       => $detailPanier,
                  'totat'       => $total,
                  'quantite'    => $quantite,
                  'presPagines' => $presentPagine,
                  'contPagine'  => $contactPagine,
                  'catePagine'  => $categoriePagine,
                  'ligncomPagine' => $ligncomPagine,
                  'commandes'   => $commandes,
                  'categories'  => $this->categorieService->allCategorie(),

                  'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),

                  'EnfantGauch' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getLft()),
                  'EnfantDroit' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getLvl()),

                  'EnfantGauch1' => $this->utilisateurService->recupeUtilisateure($EnfantGauch->getLft()),
                  'EnfantGauch2' => $this->utilisateurService->recupeUtilisateure($EnfantGauch->getLvl()),

                  'EnfantDroit1' => $this->utilisateurService->recupeUtilisateure($EnfantDroit->getLft()),
                  'EnfantDroit2' => $this->utilisateurService->recupeUtilisateure($EnfantDroit->getLvl()),

                  'EnfantGauch11' => $this->utilisateurService->recupeUtilisateure($EnfantGauch1->getLft()),
                  'EnfantGauch12' => $this->utilisateurService->recupeUtilisateure($EnfantGauch1->getLvl()),

                  'EnfantGauch21' => $this->utilisateurService->recupeUtilisateure($EnfantGauch2->getLft()),
                  'EnfantGauch22' => $this->utilisateurService->recupeUtilisateure($EnfantGauch2->getLvl()),

                  'EnfantDroit11' => $this->utilisateurService->recupeUtilisateure($EnfantDroit1->getLft()),
                  'EnfantDroit12' => $this->utilisateurService->recupeUtilisateure($EnfantDroit1->getLvl()),

                  'EnfantDroit21' => $this->utilisateurService->recupeUtilisateure($EnfantDroit2->getLft()),
                  'EnfantDroit22' => $this->utilisateurService->recupeUtilisateure($EnfantDroit2->getLvl()),

                  'allUser'     => $this->utilisateurService->allUtilisateur(),
                  'last'        => $this->utilisateurService->lastUtilisateur(),
                  'emails'      => count($this->contactService->ContactUserRecu()),
            ]);

        }

        $arrPl = $this->arrierPlanService->arrierePlan(1)->getFilename();

             return $this->render('Nzoe/userLa.html.twig', [
                 'Slids'       => $this->slideService->allSlides(),
                 'arrPls'      => $arrPl,
                 'pagineArrPl' => $arrPlPagine,
                 'pagineUtill' => $utilPagine,
                 'pagineCotis' => $cotisPagine,
                 'slidePagine' => $slidePagine,
                 'artiPagines' => $articlPagine,
                 'presPagines' => $presentPagine,
                 'prodPagines' => $produitPagine,
                 'commPagines' => $commentPagine,
                 'coulePagine' => $couleurPagine,
                 'quantite'    => $quantite,
                 'newlePagine' => $newletPagine,
                 'couleurs'    => $this->couleurService->couleur(),
                 'param'       => $param,
                 'choix'       => $choix,
                 'items'       => $detailPanier,
                 'totat'       => $total,
                 'contPagine'  => $contactPagine,
                 'catePagine'  => $categoriePagine,
                 'ligncomPagine' => $ligncomPagine,
                 'commandes'   => $commandes,
                 'allCommandes'=> $allcommandes??'',
                 'categories'  => $this->categorieService->allCategorie(),

                 'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),

                 'EnfantGauch' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getLft():''),
                 'EnfantDroit' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getLvl():''),

                 'EnfantGauch1' => $this->utilisateurService->recupeUtilisateure($EnfantGauch->getLft()),
                 'EnfantGauch2' => $this->utilisateurService->recupeUtilisateure($EnfantGauch->getLvl()),

                 'EnfantDroit1' => $this->utilisateurService->recupeUtilisateure($EnfantDroit->getLft()),
                 'EnfantDroit2' => $this->utilisateurService->recupeUtilisateure($EnfantDroit->getLvl()),

                 'EnfantGauch11' => $this->utilisateurService->recupeUtilisateure($EnfantGauch1->getLft()),
                 'EnfantGauch12' => $this->utilisateurService->recupeUtilisateure($EnfantGauch1->getLvl()),

                 'EnfantGauch21' => $this->utilisateurService->recupeUtilisateure($EnfantGauch2->getLft()),
                 'EnfantGauch22' => $this->utilisateurService->recupeUtilisateure($EnfantGauch2->getLvl()),

                 'EnfantDroit11' => $this->utilisateurService->recupeUtilisateure($EnfantDroit1->getLft()),
                 'EnfantDroit12' => $this->utilisateurService->recupeUtilisateure($EnfantDroit1->getLvl()),

                 'EnfantDroit21' => $this->utilisateurService->recupeUtilisateure($EnfantDroit2->getLft()),
                 'EnfantDroit22' => $this->utilisateurService->recupeUtilisateure($EnfantDroit2->getLvl()),

                 'allUser'     => $this->utilisateurService->allUtilisateur(),
                 'last'        => $this->utilisateurService->lastUtilisateur(),
                 'emails'      => count($this->contactService->ContactUserRecu()),
             ]);
    }


    /**
     * @Route("/logout", name="logout")
     * @throws Exception
     */
    public function logout()
    {
        throw new Exception("Don't forget to activate logout in security.yaml");
    }

    /**
     * @Route("/Nombre/mail", name="nbr_nombre")
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function emailrecu(): Response
    {
        return $this->render('base.html.twig',[
            'emails'      => count($this->contactService->ContactUserRecu()),
            'couleurs'    => $this->couleurService->couleur(),
        ]);

    }
}

<?php

namespace App\Controller;

use App\Persister\CotisationPersister;
use App\Persister\EmpreintePersister;
use App\Repository\UtilisateurRepository;
use App\Services\AccueilService;
use App\Services\SlideService;
use App\Services\ArticleService;
use App\Services\ContactService;
use App\Services\CouleurService;
use App\Services\ProduitService;
use App\Services\CategorieService;
use App\Services\ArrierPlanService;
use App\Services\UtilisateurService;
use App\Services\PresentationService;
use App\Services\Panier\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @var AccueilService
     */
    private $accueilService;
    /**
     * @var ArrierPlanService
     */
    private $arrierPlanService;
    /**
     * @var SlideService
     */
    private $slideService;
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;
    /**
     * @var ArticleService
     */
    private $articleService;
    /**
     * @var ProduitService
     */
    private $produitService;
    /**
     * @var PresentationService
     */
    private $presentationService;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var ContactService
     */
    private $contactService;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var CategorieService
     */
    private $categorieService;
    /**
     * @var PanierService
     */
    private $panierService;

    /**
     * AccueilController constructor.
     *
     * @param ArrierPlanService   $arrierPlanService
     * @param SlideService        $slideService
     * @param UtilisateurService  $utilisateurService
     * @param ArticleService      $articleService
     * @param ProduitService      $produitService
     * @param PresentationService $presentationService
     * @param CouleurService      $couleurService
     * @param ContactService      $contactService
     * @param Security            $security
     * @param CategorieService    $categorieService
     */
    public function __construct(
        AccueilService      $accueilService,
        ArrierPlanService   $arrierPlanService,
        SlideService        $slideService,
        UtilisateurService  $utilisateurService,
        ArticleService      $articleService,
        ProduitService      $produitService,
        PresentationService $presentationService,
        CouleurService      $couleurService,
        ContactService      $contactService,
        Security            $security,
        CategorieService    $categorieService,
        PanierService       $panierService

    ) {
        $this->accueilService      = $accueilService;
        $this->arrierPlanService   = $arrierPlanService;
        $this->slideService        = $slideService;
        $this->utilisateurService  = $utilisateurService;
        $this->articleService      = $articleService;
        $this->produitService      = $produitService;
        $this->presentationService = $presentationService;
        $this->couleurService      = $couleurService;
        $this->contactService      = $contactService;
        $this->security            = $security;
        $this->categorieService    = $categorieService;
        $this->panierService       = $panierService;
    }

    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request, UtilisateurRepository $utilisateurRepository, EmpreintePersister $empreintePersister, CotisationPersister $cotisationPersister): Response
    {

        $builder = $this->createFormBuilder();

        $builder->add('name');

        $form = $builder->getForm();

        $form->handleRequest($request);

        $login = $form->getData();

        $formView = $form->createView();

        $detailPanier = $this->panierService->getDetailItem();
        $quantite = $this->panierService->getQuantite();

        $user = $utilisateurRepository->findOneBy(['login' => $login]);
        $parent = $utilisateurRepository->findOneBy(['login' => 'mba@gmail.com']);

        dump($parent);
        dump($this->accueilService->FindChildren($user->getId()));

        if (!is_null($user) && $user->getId() !== $parent->getId()) {
            $empreintePersister->EmpreinteSave($parent, $user);
            if (count($this->accueilService->FindChildren($user->getId())) > 13) {
                $cotisationPersister->CotisationSave($parent);
            }
        }


        return $this->render('accueil/index.html.twig', [
            'Slids'      => $this->slideService->allSlides(),
            'Articles'   => $this->articleService->allArticle(),
            'Produits'   => $this->produitService->allProduits(),
            'Present'    => $this->presentationService->allPresent(),
            'ArrPlan'    => $this->arrierPlanService->allArrierePlan(),
            'couleurs'   => $this->couleurService->couleur(),
            'emails'     => count($this->contactService->ContactUserRecu()),
            'Categories' => $this->categorieService->allCategorie(),
            'items'      => $detailPanier,
            'quantite'   => $quantite,
            'formView'   => $formView,
        ]);
    }
}

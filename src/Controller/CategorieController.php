<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Services\CategorieService;
use App\Services\CouleurService;
use App\Services\Panier\PanierService;
use App\Services\ProduitService;
use App\Services\SlideService;
use App\Services\UtilisateurService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorie", name="semence_")
 */
class CategorieController extends AbstractController
{

    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;
    /**
     * @var SlideService
     */
    private $slideService;
    /**
     * @var CategorieService
     */
    private $categorieService;
    /**
     * @var ProduitService
     */
    private $produitService;
    /**
     * @var PanierService
     */
    private $panierService;

    public function __construct(
        CouleurService      $couleurService,
        UtilisateurService  $utilisateurService,
        SlideService        $slideService,
        CategorieService    $categorieService,
        ProduitService      $produitService,
        PanierService       $panierService
    ){
        $this->couleurService      = $couleurService;
        $this->utilisateurService  = $utilisateurService;
        $this->slideService        = $slideService;
        $this->categorieService    = $categorieService;
        $this->produitService      = $produitService;
        $this->panierService       = $panierService;
    }
    /**
     * @Route("/", name="categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        $detailPanier = $this->panierService->getDetailItem();
        $quantite = $this->panierService->getQuantite();
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findBy(['afficher'=>1]),
            'couleurs'   => $this->couleurService->couleur(),
            'items'      => $detailPanier,
            'quantite'      => $quantite,
        ]);
    }

    /**
     * @Route("/new", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        $param    = 'Categorie';
        $news     = 'Nouvelle Catégorie';

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $categorie->setCreatedAt(new \DateTime('now'));
            $categorie->setFilename('Pas Grand Chose pour le moment');

            try {
                $entityManager->persist($categorie);
                $entityManager->flush();
                $this->addFlash('success','Catégorie ajoutée !');
            }catch (\Exception $exception){
                $this->addFlash('error','Catégorie ajoutée !'. $exception->getMessage());
            }

            return $this->redirectToRoute('Essap', ['param'=>$param, 'choix'=>'Accueil']);
        }

        return $this->render('Nzoe/userLa.html.twig', [
            'categorie'   => $categorie,
            'form'        => $form->createView(),
            'couleurs'    => $this->couleurService->couleur(),
            'Slids'       => $this->slideService->allSlides(),
            'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->getUser()?$this->getUser()->getId():''),
            'news'        => $news,
            'param'       => $param
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        $produitByCategorie = $this->produitService->allProduitByCat($categorie->getId());
        $param        = 'Categorie';
        $show         = 'Nouvelle Catégorie';
        $detailPanier = $this->panierService->getDetailItem();
        $quantite     = $this->panierService->getQuantite();

        return $this->render('categorie/show.html.twig', [
            'categorie'   => $categorie,
            'produits'    => $produitByCategorie,
            'couleurs'    => $this->couleurService->couleur(),
            'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->getUser()?$this->getUser()->getId():''),
            'Slids'       => $this->slideService->allSlides(),
            'show'        => $show,
            'param'       => $param,
            'items'       => $detailPanier,
            'quantite'    => $quantite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categorie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categorie $categorie, int $id): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        $edit  = 'Edit Catégorie';
        $param = 'Categorie';

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $categorie->setFilename($request->files->get('categorie')['imagefile']->getClientOriginalName());
                $this->categorieService->editCategorie($form->getData());
                $this->addFlash('success','Catégorie Modifiée !');
            }catch (\Exception $exception){
                $this->addFlash('error','Problème lors de la sauvegarde Catégorie !'. $exception->getMessage());
            }

            return $this->redirectToRoute('Essap', ['param'=>$param, 'choix'=>'Accueil']);
        }

        return $this->render('Nzoe/userLa.html.twig', [
            'categorie'   => $categorie,
            'form'        => $form->createView(),
            'Slids'       => $this->slideService->allSlides(),
            'couleurs'    => $this->couleurService->couleur(),
            'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->getUser()?$this->getUser()->getId():''),
            'edit'        => $edit,
            'param'       => $param
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $this->categorieService->deleteCategorie($categorie->getId());
            $entityManager->flush();
        }
        $param = 'Categorie';
        return $this->redirectToRoute('Essap', ['param'=>$param, 'choix'=>'Accueil']);
    }
}

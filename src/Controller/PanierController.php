<?php

namespace App\Controller;

use App\Form\PanierConfirmationType;
use App\Services\CouleurService;
use App\Services\Panier\PanierService;
use App\Services\ProduitService;
use App\Services\ScanneProduitsService;
use App\Services\SlideService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PanierController extends AbstractController
{

    /**
     * @var PanierService
     */
    private $panierService;
    /**
     * @var ProduitService
     */
    private $produitService;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var ScanneProduitsService
     */
    private $scanneProduitsService;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var SlideService
     */
    private $slideService;

    public function __construct(
        PanierService         $panierService,
        ProduitService        $produitService,
        Security              $security,
        ScanneProduitsService $scanneProduitsService,
        CouleurService        $couleurService,
        SlideService          $slideService
    ){
        $this->panierService  = $panierService;
        $this->produitService = $produitService;
        $this->security       = $security;
        $this->scanneProduitsService = $scanneProduitsService;
        $this->couleurService = $couleurService;
        $this->slideService   = $slideService;
    }
    #region Ajouter un produit au Panier

    /**
     * Ajouter un produit au Panier
     * @Route("/panier/eve/{id}-{titre}", name="panier_eve_biom", requirements={"id":"\d+","titre":"\D+"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addProduit(Request $request): Response
    {
        $id    = (integer)$request->get('id');
        $titre = $request->get('titre');
        $produit = $this->produitService->findProduitIdTitre($id, $titre);

        if (!$produit) {
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }

        try {
            $this->panierService->addPanier($produit->getId());
            $this->addFlash('success',' Le produit a bien été ajouté au panier');
        }catch (\Exception $e){

            $this->addFlash('error',"Le produit n'a pas été ajouté au panier " . $e->getMessage());
        }

        $route = $request->query->get('returnToCart');

         if ($route == "false"){
             return $this->redirectToRoute('accueil.produit.elere',['id'=>$produit->getId()]);
         }
             return $this->redirectToRoute('show_elere');

    }
    #endregion

    #region Voir le Panier
    /**
     * @Route("/panier", name="show_elere")
     */
    public function index(): Response
    {
        $detailPanier = $this->panierService->getDetailItem();
        $total        = $this->panierService->getTotal();
        $slides       = $this->slideService->allSlides();
        $form         = $this->createForm(PanierConfirmationType::class);
        $quantite     = $this->panierService->getQuantite();

        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'couleurs'        => $this->couleurService->couleur(),
            'items'           => $detailPanier,
            'totat'           => $total,
            'slides'          => $slides,
            'quantite'        => $quantite,
            'form'  => $form->createView(),
        ]);
    }
    #endregion

    #region Supprimer un produit du Panier
    /**
     *
     * @Route("/panier/eva/{titre}/{id}", name="panier_eva_biom", requirements={"id":"\d+"})
     * @param         $id
     * @param Request $request
     *
     * @return Response
     */
    public function deleteProduit($id, Request $request): Response
    {
        $titre = $this->produitService->produit($id);
        $route = $request->query->get('returnToCart');

        if (! $titre) {
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }

        $this->panierService->remove($id);
        $this->addFlash('success', 'Le produit a bien été supprimer du panier !');

        if ($route == "false"){
            return $this->redirectToRoute('accueil.produit.elere',['id'=>$titre->getId()]);
        }
        return $this->redirectToRoute('show_elere');
    }
    #endregion

    #region decrémenter un produit du Panier
    /**
     * Retirer les produits du panier
     *
     * @Route("/panier/decrement/{id}", name="esere", requirements={"id":"\d+"})
     * @param         $id
     * @param Request $request
     *
     * @return Response
     */
    public function decrementProduit($id, Request $request): Response
    {
        $site = $this->produitService->produit($id);
        if (! $site) {
            throw $this->createNotFoundException("Le produit $id n'existe pas il ne peut donc pas être décrémenté!");
        }

        $this->panierService->decrement($id);
        $this->addFlash('success', 'Le produit a bien été retiré du panier !');
        $titre = $this->produitService->produit($id);

        $route = $request->query->get('returnToCart');

        if ($route == "false"){
            return $this->redirectToRoute('show_elere');
        }
        return $this->redirectToRoute('accueil.produit.elere',['id'=>$titre->getId()]);

    }
    #endregion
}

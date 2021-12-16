<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ContactType;
use App\Form\ProduitType;
use App\Services\CategorieService;
use App\Services\ContactService;
use App\Services\CouleurService;
use App\Services\Panier\PanierService;
use App\Services\PresentationService;
use App\Services\ProduitService;
use App\Services\SlideService;
use App\Services\UtilisateurService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class ProduitController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;
    /**
     * @var ProduitService
     */
    private $produitService;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var PresentationService
     */
    private $presentationService;
    /**
     * @var SlideService
     */
    private $slideService;
    /**
     * @var ContactService
     */
    private $contactService;
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;

    private static $edit  = 'Edit';
    private static $param = 'Produits';
    private static $choix = 'Accueil';
    private static $news  = 'News';
    private $logger;
    private $categorieService;
    /**
     * @var PanierService
     */
    private $panierService;

    /**
     * ProduitController constructor.
     *
     * @param ProduitService      $produitService
     * @param Security            $security
     * @param CouleurService      $couleurService
     * @param PresentationService $presentationService
     * @param SlideService        $slideService
     * @param ContactService      $contactService
     * @param UtilisateurService  $utilisateurService
     * @param CategorieService    $categorieService
     * @param LoggerInterface     $logger
     * @param PanierService       $panierService
     */
    public function __construct(
        ProduitService      $produitService,
        Security            $security,
        CouleurService      $couleurService,
        PresentationService $presentationService,
        SlideService        $slideService,
        ContactService      $contactService,
        UtilisateurService  $utilisateurService,
        CategorieService    $categorieService,
        LoggerInterface     $logger,
        PanierService       $panierService
    ){
        $this->security            = $security;
        $this->produitService      = $produitService;
        $this->couleurService      = $couleurService;
        $this->presentationService = $presentationService;
        $this->slideService        = $slideService;
        $this->contactService      = $contactService;
        $this->utilisateurService  = $utilisateurService;
        $this->categorieService    = $categorieService;
        $this->logger              = $logger;
        $this->panierService       = $panierService;
    }
//Afficher les Presentations

    /**
     * Cette methode permet d'afficher les Produits
     *
     * @Route("Produit/{id}", name="accueil.produit.elere", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function afficheProduit(Request $request,int $id): Response
    {
        $produit    = $this->produitService->produit($id);
        $allProduit = $this->produitService->allProduits();
        $contact    = $this->contactService->initialiserContact();
        $quantite   = $this->panierService->getQuantite();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        $this->logger->info('Exemple Log :');

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
        $detailPanier = $this->panierService->getDetailItem();
        $total = $this->panierService->getTotal();

        return $this->render('pages/Produits/produit.html.twig', [

            'couleurs'    => $this->couleurService->couleur(),
            'Present'     => $this->presentationService->allPresent(),
            'produits'    => $produit,
            'choix'       => self::$choix,
            'items'       => $detailPanier,
            'allProduit'  => $allProduit,
            'quantite'    => $quantite,
            'totat'       => $total,
            'Slids'       => $this->slideService->allSlides(),
            'form'        => $form->createView(),
            'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
            'emails'      => count($this->contactService->ContactUserRecu()),
        ]);
    }

    /**
     * Pour Ajouter un Produit
     * @Route("/Semence/new/Produit/",name="admin.new.produit")
     *
     * @param Request $request
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function newProduit(Request $request): Response
    {
        $produit = $this->produitService->initProduit();

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $this->logger->info('Exemple Log :');
                $this->produitService->addProduit($form->getData());
                $this->addFlash('success', 'Produit Ajouté avec succèss !');
            } catch (Exception $exception){
                $this->addFlash('error', $exception->getMessage());
            }
            return $this->redirectToRoute('Essap', ['param'=>self::$param, 'choix'=>self::$choix]);
        }

        return $this->render(
            'Nzoe/userLa.html.twig',
            [
                'param'       => self::$param,
                'news'        => self::$news,
                'Slids'       => $this->slideService->allSlides(),
                'form'        => $form->createView(),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                'couleurs'    => $this->couleurService->couleur(),
                'emails'      => count($this->contactService->ContactUserRecu()),
            ]
        );
    }

    /**
     * Pour editer un Produit
     *
     * @Route("/Essap/edit/Produit/{param}/{id}", name="admin.edit.produit", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function editProduit(Request $request, int $id): Response
    {

        $produit = $this->produitService->produit($id);
        $this->logger->info('Exemple Log :');

        if (self::$param != 'Produits'){
            $this->addFlash("error","Ce Parametre n'existe pas !");
        }

        $prodEdit = "Editer un Produit";

        $form     = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $this->produitService->editProduit($form->getData());
                $this->addFlash('success', 'Produit Modifié avec succès !');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }

            return $this->redirectToRoute('admin.edit.produit', [
                'param'       => self::$param,
                'id'          => $id,
                'produit'     => $produit,
                'Slids'       => $this->slideService->allSlides(),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                'couleurs'    => $this->couleurService->couleur(),
                'emails'      => count($this->contactService->ContactUserRecu()),
            ]);
        }

        if ($this->security->getUser()->getRoles() == ['ROLE_USER']) {

            return $this->render('Nzoe/userFo.html.twig',[
                'form'         => $form->createView(),
                'edit'         => self::$edit,
                'param'        => self::$param,
                'choix'        => self::$choix,
                'Produits'     => $produit,
                'prodEdit'     => $prodEdit,
                'couleurs'     => $this->couleurService->couleur(),
                'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                'Slids'        => $this->slideService->allSlides(),
                'emails'       => count($this->contactService->ContactUserRecu()),

            ]);

        }

        if ($this->security->getUser()->getRoles() == ['ROLE_ADMIN']) {

            return $this->render(
                'Nzoe/userBet.html.twig',
                [
                    'param'       => self::$param,
                    'edit'        => self::$edit,
                    'form'        => $form->createView(),
                    'couleurs'    => $this->couleurService->couleur(),
                    'Slids'       => $this->slideService->allSlides(),
                    'prodEdit'    => $prodEdit,
                    'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                    'emails'      => count($this->contactService->ContactUserRecu()),
                ]
            );
        } else {

            return $this->render(
                'Nzoe/userLa.html.twig',
                [
                    'param'       => self::$param,
                    'edit'        => self::$edit,
                    'form'        => $form->createView(),
                    'couleurs'    => $this->couleurService->couleur(),
                    'Slids'       => $this->slideService->allSlides(),
                    'prodEdit'    => $prodEdit,
                    'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                    'emails'      => count($this->contactService->ContactUserRecu()),
                ]
            );
        }
    }

    /**
     * Pour supprimer un Produit
     *
     * @Route("/Semence/delete/Produit/{id}",name="admin.delete.produit", requirements={"id":"\d+"})
     *
     * @param Request     $request
     * @param Produit     $produit
     * @param             $id
     *
     * @return Response
     */
    public function deleteProduit(Request $request, Produit $produit, $id): Response
    {
        if ($this->isCsrfTokenValid('delete'. $produit->getId(), $request->get('_token'))) {
            $this->logger->info('Exemple Log :');
            try {
                $this->produitService->deleteProduit($id);
                $this->addFlash('success', 'Produit Supprimé avec succès!');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }
        return $this->redirectToRoute('Essap', ['param'=>self::$param, 'choix'=>self::$choix]);
    }

}
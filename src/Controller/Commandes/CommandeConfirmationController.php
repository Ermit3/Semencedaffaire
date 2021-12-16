<?php

namespace App\Controller\Commandes;

use App\Entity\CommandeClient;
use App\Entity\LigneComande;
use App\Form\PanierConfirmationType;
use App\Services\CommandesClientService;
use App\Services\CouleurService;
use App\Services\LigneCommandesService;
use App\Services\Panier\PanierService;
use App\Services\Panier\PercisteCommandeService;
use App\Services\SlideService;
use App\Services\UtilisateurService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CommandeConfirmationController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PanierService
     */
    private $panierService;
    /**
     * @var PercisteCommandeService
     */
    private $percisteCommandeService;
    /**
     * @var SlideService
     */
    private $slideService;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var LigneCommandesService
     */

    /**
     * @var UtilisateurService
     */
    private $utilisateurService;
    /**
     * @var CommandesClientService
     */
    private $commandesClientService;

    private static $edit  = 'CommandeEdit';
    private static $param = 'Commandes';
    /**
     * @var LigneCommandesService
     */
    private $ligneCommandesService;
    /**
     * @var Security
     */
    private $security;

    /**
     * CommandeConfirmationController constructor.
     *
     * @param PanierService           $panierService
     * @param EntityManagerInterface  $em
     * @param PercisteCommandeService $percisteCommandeService
     * @param SlideService            $slideService
     * @param CouleurService          $couleurService
     * @param UtilisateurService      $utilisateurService
     * @param CommandesClientService  $commandesClientService
     * @param LigneCommandesService   $ligneCommandesService
     * @param Security                $security
     */
    public function __construct(
        PanierService           $panierService,
        EntityManagerInterface  $em,
        PercisteCommandeService $percisteCommandeService,
        SlideService            $slideService,
        CouleurService          $couleurService,
        UtilisateurService      $utilisateurService,
        CommandesClientService  $commandesClientService,
        LigneCommandesService   $ligneCommandesService,
        Security                $security
    )
    {
        $this->panierService  = $panierService;
        $this->em = $em;
        $this->percisteCommandeService = $percisteCommandeService;
        $this->slideService   = $slideService;
        $this->couleurService = $couleurService;
        $this->utilisateurService = $utilisateurService;
        $this->commandesClientService = $commandesClientService;
        $this->ligneCommandesService = $ligneCommandesService;
        $this->security = $security;
    }

    /**
     * @Route("/Commande/Confirm/Panier", name="Commande_Panier")
     * @param Request           $request
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY", message="Vous devez être connecter pour confirmer une commande! ")
     *
     * @return RedirectResponse
     */
    public function confirmeCommande(Request $request)
    {
       // 1. Nous allons lire les données du formulaire

        $form = $this->createForm(PanierConfirmationType::class);
        $form->handleRequest($request);
       // 2. Si le formulaire n'a pas été soumis : dégage

        if (!$form->isSubmitted()){
            $this->addFlash('warning', 'Vous devez remplir le formulaire de confirmation');
            return $this->redirectToRoute('show_elere');

        }
       // 4. S'il n'y a pas de produits : dégage
        $panieItem = $this->panierService->getDetailItem();

        if (0 === count($panieItem)) {
            $this->addFlash('warning', 'Vous ne pouvez confirmer une commande avec un panier vide !');
             return $this->redirectToRoute('show_elere');
        }
       // 5. Nous allons creer une commande
        /** @var CommandeClient $commande */
         $commande = $form->getData();
        try {

            $this->percisteCommandeService->perciste($commande);
            $this->addFlash('success', 'Commande enregistrée, nous vous contacterons pour la livraison par Mail et par Téléphone !');
        } catch (\Exception $exception){
            $this->addFlash('error', $exception->getMessage());
        }

        return $this->redirectToRoute('accueil');
    }

    #region Voir le Panier
    /**
     * @Route("/Commande/edit/{id}", name="elere_commande")
     */
    public function editCommande(Request $response): Response
    {
        $id           = (integer)$response->get('id');
        $ligneComman  = $this->ligneCommandesService->ligneComande($id);

        $commande     = $this->commandesClientService->CommandeClient($ligneComman->getCommande()->getId());
        $form         = $this->createForm(PanierConfirmationType::class,$commande);
        $detailPanier = $this->panierService->getDetailItem();
        $quantite     = $this->panierService->getQuantite();

        return $this->render(
            'Nzoe/userLa.html.twig',
            [
                'param'       => self::$param,
                'edit'        => self::$edit,
                'ligneComm'   => $ligneComman,
                'items'       => $detailPanier,
                'quantite'    => $quantite,
                'form'        => $form->createView(),
                'couleurs'    => $this->couleurService->couleur(),
                'Slids'       => $this->slideService->allSlides(),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
            ]
        );
    }
    #endregion

    #region Delete une ligne de commande
    /**
     * Pour supprimer un Article
     *
     * @Route("/Semence/delete/LigneCommande/{id}",name="admin.delete.ligneCommande", requirements={"id":"\d+"})
     *
     * @param Request      $request
     * @param LigneComande $ligneCommande
     * @param              $id
     *
     * @return Response
     * @throws \Exception
     */
    public function deleteLigneCommande(Request $request, LigneComande $ligneCommande, $id): Response
    {
        if ($this->isCsrfTokenValid('delete'. $ligneCommande->getId(), $request->get('_token'))) {
            try {
                $this->ligneCommandesService->deleteLigneComande($id);
                $this->addFlash('success', 'ligne de commande supprimée avec succès!');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }
        return $this->redirectToRoute('Essap', ['param'=>'AllCommandes', 'choix'=>'Accueil']);
    }
}
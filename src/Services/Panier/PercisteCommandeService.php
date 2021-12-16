<?php

namespace App\Services\Panier;

use App\Entity\CommandeClient;
use App\Entity\LigneComande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use DateTime;
use Exception;

class PercisteCommandeService
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
     * @var Security
     */
    private $security;

    public function __construct(EntityManagerInterface $em, PanierService $panierService, Security $security){
        $this->em = $em;
        $this->panierService = $panierService;
        $this->security = $security;
    }

    /**
     * @param CommandeClient $commande
     *
     * @return CommandeClient
     * @throws Exception
     */
    public function perciste(CommandeClient $commande) : CommandeClient
    {
        // 6. Nous allons la lier avec l'utilisateur actuellement connecté

        $commande->setUtilisateur($this->security->getUser());
        $commande->setDatelivre(new DateTime('now'));
        $commande->setTotal($this->panierService->getTotal());

        $this->em->persist($commande);
        // 7. Nous allons la lier avec les produits qui sont dans le panier

        foreach ($this->panierService->getDetailItem() as $panieritem){

            $commaneItem = new LigneComande();
            $commaneItem
                ->setCommande($commande)
                ->setProduit($panieritem->produits)
                ->setNomProduit($panieritem->produits->getTitre())
                ->setQuantite($panieritem->qty)
                ->setTotal($panieritem->getTotal())
                ->setPrixCommande($panieritem->produits->getPrix());

            $this->em->persist($commaneItem);
        }
        $commande->setQuantite($commaneItem->getQuantite());
        $commande->setAfficher(true);

        // 8. Enregitrement de la commande

        try {

            $this->em->flush();
            $this->panierService->emptyPanier();

        } catch (Exception $exception){
            throw new Exception("Erreur lors de l'enregistrement de la commande !" . $exception->getMessage());
        }

        return $commande;
    }
}
<?php

namespace App\Services\Panier;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{

    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var ProduitRepository
     */
    private $produitRepository;

    public function __construct(SessionInterface $session, ProduitRepository $produitRepository){
        $this->session = $session;
        $this->produitRepository = $produitRepository;
    }

    /**
     * Recuperer le panier de la Session
     *
     * @return array
     */
    public function getPanier(): array
    {
       return $this->session->get('panier',[]);
    }

    /**
     * Enregistrer le Panier dans la Session
     *
     * @param array $panier
     *
     * @return mixed
     */
    public function savePanier(array $panier)
    {
        return $this->session->set('panier', $panier);
    }

    /**
     * Vide Le Panier
     */
    public function emptyPanier()
    {
        $this->savePanier([]);
    }

    /**
     * Ajouter un Produit au Panier
     *
     * @param $id
     */
    public function addPanier($id)
    {
       $panier = $this->getPanier();

       if (! array_key_exists($id, $panier)) {
           $panier[$id] = 0;
       }
       $panier[$id]++;

       $this->savePanier($panier);
    }

    /**
     * Retirer un produit du panier
     *
     * @param $id
     */
    public function remove($id)
    {
        $panier = $this->getPanier();

        unset($panier[$id]);

        $this->savePanier($panier);
    }

    /**
     * Total des Produits
     *
     * @return int
     */
    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getPanier() as $id => $qty){
            $produit = $this->produitRepository->find($id);

            if (! $produit){
                continue;
            }

            $total += $produit->getPrix() * $qty;
        }

        return  $total;
    }

    /**
     * Detail du Produit
     *
     * @return PanierItem
     */
    public function getDetailItem(): array
    {
        $detailPanier = [];

        foreach ($this->getPanier() as $id => $qty) {
            $produit = $this->produitRepository->find($id);

            if (!$produit){
                continue;
            }

            $detailPanier[] = new PanierItem($produit, $qty);
        }

        return $detailPanier;
    }

    /**
     * Quantite des Produits
     *
     * @return int
     */
    public function getQuantite():int
    {
        $Qty = 0;

        foreach ($this->getPanier() as $id => $qty){
           $produit = $this->produitRepository->find($id);

           if (! $produit){
               continue;
           }

           $Qty += $qty;
        }

        return $Qty;
    }

    /**
     * Retirer un produit
     *
     * @param int $id
     *
     * @return void
     */
    public function decrement(int $id)
    {
        $panier = $this->getPanier();

        if (! array_key_exists($id, $panier)){
            return;
        }

        if (1 === $panier[$id]){
            $this->remove($id);
            return;
        }

        $panier[$id]--;

        $this->savePanier($panier);
    }

}
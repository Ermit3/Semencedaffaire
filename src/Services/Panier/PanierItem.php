<?php

namespace App\Services\Panier;

use App\Entity\Produit;

class PanierItem
{
    /**
     * @var Produit
     */
    public $produits;
    /**
     * @var int
     */
    public $qty;

    /**
     * PanierItemService constructor.
     *
     * @param Produit $produits
     * @param int      $qty
     */
    public function __construct(Produit $produits, int $qty)
    {
        $this->produits = $produits;
        $this->qty = $qty;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->produits->getPrix() * $this->qty;
    }
}
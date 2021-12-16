<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeClientRepository")
 */
class CommandeClient
{

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_PAID = 'PAID';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     *
     * @return CommandeClient
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     *
     * @return CommandeClient
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     *
     * @return CommandeClient
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     *
     * @return CommandeClient
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $ville;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_at;

    public function __construct()
    {
        $this->creation_at = new DateTime('now');
        $this->ligneComandes = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="integer")
     */
    private $reponse = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datelivre;

    /**
     * @ORM\Column(type="float", precision=8, scale=2)
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=17)
     */
    private $statutcom = 'PENDING';

    /**
     * @ORM\Column(type="boolean")
     */
    private $afficher;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="commandeClients")
     */
    private $utilisateur;

    /**
     * @ORM\OneToMany(targetEntity=LigneComande::class, mappedBy="commande", orphanRemoval=true)
     */
    private $ligneComandes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCreationAt(): ?\DateTimeInterface
    {
        return $this->creation_at;
    }

    public function setCreationAt(\DateTimeInterface $creation_at): self
    {
        $this->creation_at = $creation_at;

        return $this;
    }

    public function getReponse(): ?int
    {
        return $this->reponse;
    }

    public function setReponse(int $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getDatelivre(): ?\DateTimeInterface
    {
        return $this->datelivre;
    }

    public function setDatelivre(\DateTimeInterface $datelivre): self
    {
        $this->datelivre = $datelivre;

        return $this;
    }

    public function setRestapayer(float $restapayer): self
    {
        $this->restapayer = $restapayer;

        return $this;
    }

    public function getStatutcom(): ?string
    {
        return $this->statutcom;
    }

    public function setStatutcom(?string $statutcom): self
    {
        $this->statutcom = $statutcom;

        return $this;
    }

    public function getAfficher(): ?bool
    {
        return $this->afficher;
    }

    public function setAfficher(bool $afficher): self
    {
        $this->afficher = $afficher;

        return $this;
    }


    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }


    public function setCodePostal(string $codePostal):self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
    /**
     * Generates the magic method
     *
     */
    public function __toString(){

        return $this->fullName;

    }

    /**
     * @return Collection|LigneComande[]
     */
    public function getLigneComandes(): Collection
    {
        return $this->ligneComandes;
    }

    public function addLigneComande(LigneComande $ligneComande): self
    {
        if (!$this->ligneComandes->contains($ligneComande)) {
            $this->ligneComandes[] = $ligneComande;
            $ligneComande->setCommande($this);
        }

        return $this;
    }

    public function removeLigneComande(LigneComande $ligneComande): self
    {
        if ($this->ligneComandes->removeElement($ligneComande)) {
            // set the owning side to null (unless already changed)
            if ($ligneComande->getCommande() === $this) {
                $ligneComande->setCommande(null);
            }
        }

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

}
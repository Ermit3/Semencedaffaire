<?php

namespace App\Entity;

use App\Repository\CotisationsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CotisationsRepository::class)
 * @UniqueEntity(fields={"utilisateur"}, message="Cet Nom Existe déjà !")
 * UniqueEntity(fields={"montant"}, message="De cet utilisateur ce Montant Existe déjà !")
 */
class Cotisations
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", precision=10, scale=2)
     */
    private $montant;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     */
    private $source;

    /**
     * @ORM\Column(type="boolean")
     */
    private $afficher;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex(pattern="/\s/", match=false, message="Votre téléphone ne doit contenir de caracteres")
     */
    private $facture;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idfiche;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Quartier;

    /**
     * @ORM\Column(type="string", length=18, nullable=true)
     * @Assert\Regex(pattern="/\s/", match=false, message="Votre téléphone ne doit contenir de caracteres")
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nationalite;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nomsponsor;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $prenomsponsor;

    /**
     * @ORM\Column(type="string", length=18, nullable=true)
     * @Assert\Regex(pattern="/\s/", match=false, message="Votre téléphone ne doit contenir de caracteres")
     */
    private $telephonesponsor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idsponsor;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, cascade={"persist"})
     */
    private $utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getSource(): ?Utilisateur
    {
        return $this->source;
    }

    public function setSource(?Utilisateur $source): self
    {
        $this->source = $source;

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

    public function getFacture(): ?int
    {
        return $this->facture;
    }

    public function setFacture(int $facture): self
    {
        $this->facture = $facture;

        return $this;
    }

    public function getIdfiche(): ?int
    {
        return $this->idfiche;
    }

    public function setIdfiche(?int $idfiche): self
    {
        $this->idfiche = $idfiche;

        return $this;
    }

    public function getQuartier(): ?string
    {
        return $this->Quartier;
    }

    public function setQuartier(?string $Quartier): self
    {
        $this->Quartier = $Quartier;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(?string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getNomsponsor(): ?string
    {
        return $this->nomsponsor;
    }

    public function setNomsponsor(?string $nomsponsor): self
    {
        $this->nomsponsor = $nomsponsor;

        return $this;
    }

    public function getPrenomsponsor(): ?string
    {
        return $this->prenomsponsor;
    }

    public function setPrenomsponsor(?string $prenomsponsor): self
    {
        $this->prenomsponsor = $prenomsponsor;

        return $this;
    }

    public function getTelephonesponsor(): ?string
    {
        return $this->telephonesponsor;
    }

    public function setTelephonesponsor(?string $telephonesponsor): self
    {
        $this->telephonesponsor = $telephonesponsor;

        return $this;
    }

    public function getIdsponsor(): ?int
    {
        return $this->idsponsor;
    }

    public function setIdsponsor(?int $idsponsor): self
    {
        $this->idsponsor = $idsponsor;

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
}

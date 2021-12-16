<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 * @Vich\Uploadable()
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filenameface;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filenamedos;
    /**
     * @var File|null
     * @Vich\UploadableField(mapping="produit_image", fileNameProperty="filenameface")
     */
    private $imagefile;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="produit_image", fileNameProperty="filenamedos")
     */
    private $imagefiledos;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $soustext;

    /**
     * @ORM\Column(type="boolean")
     */
    private $afficher;

    /**
     * @ORM\Column(type="datetime")
     */
    private $upload_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     */
    private $source;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prix;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param File|null $imagefiledos
     *
     * @return Produit
     */
    public function setImagefiledos(?File $imagefiledos): Produit
    {
        $this->imagefiledos = $imagefiledos;
        if ($this->imagefiledos instanceof UploadedFile){
            $this->upload_at = new DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImagefiledos(): ?File
    {
        return $this->imagefiledos;
    }

    /**
     * @return File|null
     */
    public function getImagefile(): ?File
    {
        return $this->imagefile;
    }

    /**
     * @param File|null $imagefile
     *
     * @return Produit
     */
    public function setImagefile(?File $imagefile): Produit
    {
        $this->imagefile = $imagefile;
        if ($this->imagefile instanceof UploadedFile){
            $this->upload_at = new DateTime('now');
        }

        return $this;
    }

    public function getFilenameface(): ?string
    {
        return $this->filenameface;
    }

    public function setFilenameface(?string $filenameface): self
    {
        $this->filenameface = $filenameface;

        return $this;
    }

    public function getFilenamedos(): ?string
    {
        return $this->filenamedos;
    }

    public function setFilenamedos(?string $filenamedos): self
    {
        $this->filenamedos = $filenamedos;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getSoustext(): ?string
    {
        return $this->soustext;
    }

    public function setSoustext(?string $soustext): self
    {
        $this->soustext = $soustext;

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

    public function getUploadAt(): ?\DateTimeInterface
    {
        return $this->upload_at;
    }

    public function setUploadAt(\DateTimeInterface $upload_at): self
    {
        $this->upload_at = $upload_at;

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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(?string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function __toString(){
        return $this->titre;
    }
}

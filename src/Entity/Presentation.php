<?php

namespace App\Entity;

use App\Repository\PresentationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PresentationRepository::class)
 * @Vich\Uploadable()
 */
class Presentation
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
    private $filename;
    /**
     * @var File|null
     * @Vich\UploadableField(mapping="present_image", fileNameProperty="filename")
     */
    private $imagefile;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $soustitre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $texte;

    /**
     * @ORM\Column(type="boolean")
     */
    private $afficher;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;
    public function __construct()
    {
        $this->create_at = new DateTime('now');
    }
    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     */
    private $utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSoustitre(): ?string
    {
        return $this->soustitre;
    }

    public function setSoustitre(?string $soustitre): self
    {
        $this->soustitre = $soustitre;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(?string $texte): self
    {
        $this->texte = $texte;

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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

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
     * @return File|null
     */
    public function getImagefile(): ?File
    {
        return $this->imagefile;
    }

    /**
     * @param File|null $imagefile
     *
     * @return Presentation
     */
    public function setImagefile(?File $imagefile): Presentation
    {
        $this->imagefile = $imagefile;
        if ($this->imagefile instanceof UploadedFile){
            $this->create_at = new DateTime('now');
        }
        return $this;
    }

    public function __toString(){
        return $this->filename;
    }
}

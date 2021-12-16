<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 * @Vich\Uploadable()
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $upload_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="categorie_image", fileNameProperty="filename")
     */
    private $imagefile;

    /**
     * @ORM\Column(type="boolean")
     */
    private $afficher;

    public function __construct()
    {
        $this->create_at = new DateTime('now');
    }
    /**
     * @param DateTime $create_at
     *
     * @return Categorie
     */
    public function setCreateAt(DateTime $create_at): Categorie
    {
        $this->create_at = $create_at;

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
     * @return DateTime
     */
    public function getCreateAt(): DateTime
    {
        return $this->create_at;
    }

    /**
     * @param File|null $imagefile
     *
     * @return Categorie
     */
    public function setImagefile(?File $imagefile): Categorie
    {
        $this->imagefile = $imagefile;
        if ($this->imagefile instanceof UploadedFile){
            $this->upload_at = new DateTime('now');
        }
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUploadAt()
    {
        return $this->upload_at;
    }

    /**
     * @param mixed $upload_at
     *
     * @return Categorie
     */
    public function setUploadAt($upload_at)
    {
        $this->upload_at = $upload_at;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUser(): ?Utilisateur
    {
        return $this->user;
    }

    public function setUser(?Utilisateur $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
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

    public function getAfficher(): ?bool
    {
        return $this->afficher;
    }

    public function setAfficher(bool $afficher): self
    {
        $this->afficher = $afficher;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ArrierePlanRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ArrierePlanRepository::class)
 *
 * @UniqueEntity("nom", message="Ce Nom Existe déjà")
 * @UniqueEntity("filename", message="Cette Image Existe déjà")
 *
 * @Vich\Uploadable()
 */
class ArrierePlan
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
    private $filename;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="arrpl_image", fileNameProperty="filename")
     */
    private $imagefile;
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
        $this->creation_at = new DateTime('now');
    }
    /**
     * @ORM\Column(type="datetime")
     */
    private $upload_at;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $source;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

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

    public function getUploadAt(): ?\DateTimeInterface
    {
        return $this->upload_at;
    }

    public function setUploadAt(\DateTimeInterface $upload_at): self
    {
        $this->upload_at = $upload_at;

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

    public function __toString(){
        return $this->nom;
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
     * @return ArrierePlan
     */
    public function setImagefile(?File $imagefile): ArrierePlan
    {
        $this->imagefile = $imagefile;
        if ($this->imagefile instanceof UploadedFile){
            $this->upload_at = new \DateTime('now');
        }
        return $this;
    }
}

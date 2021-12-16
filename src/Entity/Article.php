<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @Vich\Uploadable()
 */
class Article
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
     * @var File|null
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="filename")
     */
    private $imagefile;

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
    public function __construct()
    {
        $this->create_at = new DateTime('now');
    }
    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
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
     * @return Article
     */
    public function setImagefile(?File $imagefile): Article
    {
        $this->imagefile = $imagefile;
        if ($this->imagefile instanceof UploadedFile){
            $this->upload_at = new DateTime('now');
        }
        return $this;
    }
}

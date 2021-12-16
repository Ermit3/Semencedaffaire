<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mailsource;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $maildestination;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="boolean")
     */
    private $afficher;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $idrepondre;

    /**
     * @ORM\ManyToOne(targetEntity=Contact::class, inversedBy="reponse")
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity=Commentaire::class, inversedBy="reponse")
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     */
    private $source;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMailsource(): ?string
    {
        return $this->mailsource;
    }

    public function setMailsource(string $mailsource): self
    {
        $this->mailsource = $mailsource;

        return $this;
    }

    public function getMaildestination(): ?string
    {
        return $this->maildestination;
    }

    public function setMaildestination(string $maildestination): self
    {
        $this->maildestination = $maildestination;

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

    public function getIdrepondre(): ?int
    {
        return $this->idrepondre;
    }

    public function setIdrepondre(int $idrepondre): self
    {
        $this->idrepondre = $idrepondre;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getCommentaire(): ?Commentaire
    {
        return $this->commentaire;
    }

    public function setCommentaire(?Commentaire $commentaire): self
    {
        $this->commentaire = $commentaire;

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
        return $this->maildestination;
    }
}

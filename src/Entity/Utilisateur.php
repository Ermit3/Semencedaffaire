<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Serializable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 *
 * @Vich\Uploadable()
 */
class Utilisateur implements UserInterface, Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="float", precision=12, scale=2)
     */
    private $montant;

    /**
     * @ORM\ManyToMany(targetEntity=Acl::class, inversedBy="utilisateurs", cascade={"persist"})
     * @ORM\JoinTable("utilisateur_acl")
     */
    private $acl;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $groupe;

    /**
     * @ORM\ManyToOne(targetEntity=Grade::class)
     */
    private $grade;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $source;

    /**
     * @ORM\Column(type="boolean")
     */
    private $afficher;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Utilisateur::class, mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})

     */
    private $children;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="utilisateur_image", fileNameProperty="image")
     */
    private $imagefile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $upload_at;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $empreinte = [];

    public function __construct()
    {
        $this->acl = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
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

    /**
     * @return Collection|Acl[]
     */
    public function getAcl(): Collection
    {
        return $this->acl;
    }

    public function addAcl(Acl $acl): self
    {
        if (!$this->acl->contains($acl)) {
            $this->acl[] = $acl;
        }

        return $this;
    }

    public function removeAcl(Acl $acl): self
    {
        $this->acl->removeElement($acl);

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getGrade(): ?Grade
    {
        return $this->grade;
    }

    public function setGrade(?Grade $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
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

    /**
     * @return string|null
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    /**
     * @return mixed
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return string|null
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->mail,
            $this->login,
            $this->password
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        [$this->id, $this->mail, $this->login, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function __toString()
    {
        return $this->nom;
    }

    /**
     * @return int|null
     */
    public function getLft(): ?int
    {
        return $this->lft;
    }

    /**
     * @param int $lft
     * @return self
     */
    public function setLft(int $lft): self
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLvl(): ?int
    {
        return $this->lvl;
    }

    /**
     * @param int $lvl
     * @return self
     */
    public function setLvl(int $lvl): self
    {
        $this->lvl = $lvl;

        return $this;
    }

    public function getRgt(): ?int
    {
        return $this->rgt;
    }

    public function setRgt(int $rgt): self
    {
        $this->rgt = $rgt;

        return $this;
    }

    public function getRoot(): ?self
    {
        return $this->root;
    }

    public function setRoot(?self $root): self
    {
        $this->root = $root;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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
     * @return Utilisateur
     */
    public function setImagefile(?File $imagefile): Utilisateur
    {
        $this->imagefile = $imagefile;
        if ($this->imagefile instanceof UploadedFile) {
            $this->upload_at = new \DateTime('now');
        }
        return $this;
    }

    public function getUploadAt(): ?\DateTimeInterface
    {
        return $this->upload_at;
    }

    public function setUploadAt(?\DateTimeInterface $upload_at): self
    {
        $this->upload_at = $upload_at;

        return $this;
    }

    public function getEmpreinte(): ?array
    {
        return $this->empreinte;
    }

    public function setEmpreinte(?array $empreinte): self
    {
        $this->empreinte = $empreinte;

        return $this;
    }
}

<?php

namespace App\Services;

use App\Entity\Cotisations;
use App\Entity\Groupe;
use App\Entity\Utilisateur;
use App\Entity\Utilisateurs;
use App\Exception\UtilisateursException;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UtilisateurService
{

    /**
     * @var UtilisateurRepository
     */
    private $utilisateurRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var GroupeService
     */
    private $groupeService;
    /**
     * @var CotisationService
     */
    private $cotisationService;

    public function __construct(
        UtilisateurRepository $utilisateurRepository,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        Security          $security,
        GroupeService     $groupeService,
        CotisationService $cotisationService
    ){
        $this->utilisateurRepository = $utilisateurRepository;
        $this->em                = $em;
        $this->encoder           = $encoder;
        $this->security          = $security;
        $this->groupeService     = $groupeService;
        $this->cotisationService = $cotisationService;
    }

    #region Slide Existe : Bool
    /**
     * Vérifie l'Existence d'un Utilisateur via Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->utilisateurRepository->findBy(['id'=>$id]);
    }
    #endregion Utilisateur Existe

    #region Utilisateur Existent : Bool
    /**
     * Vérifie que le tableau Utilisateur ne soit pas vide
     *
     * @return bool
     */
    public function Exists(): bool
    {
        return [] != $this->utilisateurRepository->findAll();
    }
    #endregion Utilisateur Existent


    #region Utilisateur Existent mail: Bool
    /**
     * @param $mail
     *
     * @return bool
     */
    public function existsViaMail($mail): bool
    {
        return [] != $this->utilisateurRepository->findBy(['mail'=>$mail]);
    }
    #endregion Utilisateur Existent

    #region Recuperer un Utilisateur
    /**
     * Retourne un Utilisateur
     *
     * @param $id
     *
     * @return Utilisateur
     */
    public function recupeUtilisateure($id): Utilisateur
    {
        $userNull = null;

        if ($id == 0){

        }elseif ($id > 0){

            if (!$this->exist($id)){
                throw new NotFoundHttpException("Cet Utilisateur n'existe pas !");
            }
            return $this->utilisateurRepository->findOneBy(['id'=>$id]);
        }
        return $this->utilisateurRepository->findOneBy(['id'=>1]);
    }
    #endregion Utilisateur Existe : Bool

    #region Recuperer un Utilisateur
    /**
     * Retourne un Utilisateur
     *
     * @param $id
     *
     * @return Utilisateur
     */
    public function recupeUtilisateur($id): Utilisateur
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cet Utilisateur n'existe pas !");
        }
        return $this->utilisateurRepository->findOneBy(['id'=>$id]);
    }
    #endregion Utilisateur Existe : Bool

    #region Recuperer un Utilisateur
    /**
     * Retourne un Utilisateur
     *
     * @param $id
     *
     * @return Utilisateur
     */
    public function findRoleUser($id): Utilisateur
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cet Utilisateur n'existe pas !");
        }
        return $this->utilisateurRepository->findRoleUseryId($id);
    }
    #endregion Utilisateur Existe : Bool
    #region pagination
    /**
     * Pour la pagination des Arriere Plan
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->utilisateurRepository->findUtilPaginatorQuery();
    }
    #endregion

    /**
     * @param string      $param
     * @param Utilisateur $utilisateur
     *
     * @return Utilisateur
     */
    public function verifierUnUser(string $param, Utilisateur $utilisateur): Utilisateur
    {
        if (!isset($param) or $param != 'Utilisateurs'){
            throw new NotFoundHttpException("Ce Parametre n'existe pas !");
        }

        if (!($utilisateur instanceof Utilisateur)){
            throw new NotFoundHttpException("Nous ne sommes pas en présence d'une Instance utilisateur!");
        }

        return $utilisateur;
    }

    #region Tous les Utilisateurs
    /**
     * @param string $mail
     *
     * @return bool
     */
    public function utilisateurExistMail(string $mail): bool
    {
        return [] != $this->utilisateurRepository->findBy(['mail' => $mail]);
    }
    #endregion

    #region Tous les Utilisateurs
    /**
     * Retourne un Utilisateur un Fonction du Mail
     * @param string $mail
     *
     * @return Utilisateur
     */
    public function utilisateurViaMail(string $mail): Utilisateur
    {
        if (!$this->utilisateurExistMail($mail)){
            throw new NotFoundHttpException("Cet Utilisateur n'existe pas, Nous ne pouvons modifier son Password !");
        }
        return  $this->utilisateurRepository->findOneBy(['mail' => $mail]);
    }
    #endregion

    #region Recuperer Tous les Utilisateurs
    /**
     * Retourne tous les Utilisateurs
     *
     * @return array
     */
    public function allUtilisateur(): array
    {
        if (!$this->Exists()){
            throw new NotFoundHttpException("Ces Utilisateurs n'existent pas !");
        }
        return $this->utilisateurRepository->findAll();
    }
    #endregion Utilisateurs

    #region Utilisateur Initialisation
    /**
     *
     * @return Utilisateur
     */
    public function initialiserUtilisateur(): Utilisateur
    {

        return (new Utilisateur())
            ->setNom('')
            ->setPrenom('')
            ->setLogin('')
            ->setMail('')
            ->setRoles(['ROLE_USER'])
            ->setMontant(0.00)
            ->setPassword('')
            ->setStatut(1)
            ->setAfficher(false)
            ->setGroupe($this->groupeService->recupeGroupe(1))
            ->setGrade(null)
            ->setSource('')
            ;
    }
    #endregion

    #region Utilisateur Initialisation
    /**
     * Ajouter un Utilisateur
     *
     * @param array       $acls
     * @param Utilisateur $utilisateur
     *
     * @return Utilisateur
     */
    public function addUtilisateur(array $acls, Utilisateur $utilisateur): Utilisateur
    {

        if (!isset($utilisateur) or $this->existsViaMail($utilisateur->getMail())){
            throw new NotFoundHttpException("Cet utilisateur existe dejà !");
        }

        try {
            $alea  = substr(rand (1 , 9),0,1);
            $nomUs = substr($utilisateur->getNom(),0,2);
            $preUs = substr($utilisateur->getPrenom(),0,1);
            $useId = $this->lastUtilisateur()+1 < 10 ? 0 . ($this->lastUtilisateur()+1) : $this->lastUtilisateur()+1;
            $idrefUser = 'SA' . $useId . $alea . strtoupper($preUs) . strtoupper($nomUs) ;
            $utilisateur->setStatut($idrefUser);
            $password = htmlspecialchars(strip_tags($utilisateur->getPassword()));
            $utilisateur->setPassword($this->encoder->encodePassword($utilisateur, $password));
            $utilisateur->setRoot($utilisateur);
            $utilisateur->setParent($utilisateur->getParent());
            $this->initialiserUtilisateur();

            $retour = [];
            foreach ($acls as $key => $val){
                $retour[$key] = $val;
                $acl = $this->em->getRepository('App:Acl')->findOneBy(['id' => $val]);
                $utilisateur->addAcl($acl);
            }

            $newCotisation = new Cotisations();
            $newCotisation
                ->setFacture(0)
                ->setIdfiche(0)
                ->setQuartier('')
                ->setTelephone(0)
                ->setNationalite('')
                ->setNomsponsor($utilisateur->getParent()->getNom())
                ->setPrenomsponsor($utilisateur->getParent()->getPrenom())
                ->setTelephonesponsor(0)
                ->setIdsponsor($utilisateur->getParent()->getId())
                ->setMontant($utilisateur->getMontant())
                ->setCreateAt(new DateTime('now'))
                ->setAfficher(true)
                ->setUtilisateur($utilisateur)
                ->setSource($this->security->getUser());

            $newCotis = $this->cotisationService->addCotisations($newCotisation);
            $this->em->persist($newCotis);

            $utilisateur->setLft(0);
            $utilisateur->setLvl(0);
            $utilisateur->setRgt(1);

            // Ajouter la valeur de l'enfant chez le parent
            $Parent = $this->recupeUtilisateure($utilisateur->getParent()->getId());

                if ($Parent->getLft() == 0 ){
                    $Parent->setLft($utilisateur->getId());
                    $Parent->setRgt($Parent->getRgt()-1);
                }elseif ($Parent->getLvl() == 0){
                    $Parent->setLft($Parent->getLft()-2);
                    $Parent->setLvl($utilisateur->getId());
                    $Parent->setRgt($Parent->getRgt()-1);
                }else{
                    throw new NotFoundHttpException(" Ce parent à déjà ses deux Enfants, Merci d'en choisir un autre ! ");
                }

            $this->em->persist($utilisateur);
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException(" Ce parent à déjà ses deux Enfants, Merci d\'en choisir un autre ! " . $exception->getMessage());
        }
     return $utilisateur;
    }
    #endregion

    #region Edit Utilisateur
    /**
     * Edit un Utilisateur
     *
     * @param array       $acls
     * @param Utilisateur $utilisateur
     *
     * @return Utilisateur
     */
    public function editUtilisateur(array $acls, Utilisateur $utilisateur): Utilisateur
    {
        if (!isset($utilisateur) or !$this->existsViaMail($utilisateur->getMail())){
            throw new NotFoundHttpException("Cet utilisateur n'existe pas !");
        }

        try {

            $retour = [];
            foreach ($utilisateur->getAcl()->getValues() as $key => $val){
                $retour[$key] = $val;
                $acl = $this->em->getRepository('App:Acl')->findOneBy(['id' => $val]);
                $utilisateur->removeAcl($acl);
            }

            foreach ($acls as $key => $val){
                $retour[$key] = $val;
                $acl = $this->em->getRepository('App:Acl')->findOneBy(['id' => $val]);
                $utilisateur->addAcl($acl);

            }

            $password = htmlspecialchars(strip_tags($utilisateur->getPassword()));
            $utilisateur->setPassword($this->encoder->encodePassword($utilisateur, $password));
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de la sauvegarde de le Modif Utilisateur" . $exception->getMessage());
        }
        return $utilisateur;
    }
    #endregion Edit

    #region Supprimer un utilisateur
    /**
     * @param $id
     *
     * @return Utilisateur
     */
    public function deleteUtilisateur($id): Utilisateur
    {

        if (!$this->exist($id)){
            throw new NotFoundHttpException("Cet utilisateur n'existe pas !");
        }

        try {

            $cotisation = $this->cotisationService->deleteCotisationsUser($id);

            $utilisateur = $this->utilisateurRepository->find($id);
            if ($utilisateur->getId() != 1) {
                $this->em->remove($cotisation);
                $this->em->remove($utilisateur);
                $this->em->flush();
            }else{
                throw new NotFoundHttpException("Vous ne devez pas supprimer cet Utilisateur !");
            }
        } catch (Exception $exception) {
            throw new NotFoundHttpException("Pour Information !" . $exception->getMessage());
        }
        return $utilisateur;
    }
    #endregion

    #region Recuperer un Utilisateur
    /**
     * Retourne un Utilisateur
     *
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function dernierUser(): int
    {
        return $this->utilisateurRepository->nbrUser();
    }

    #region Recuperer un Utilisateur
    /**
     * Retourne un Utilisateur
     *
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function lastUtilisateur(): int
    {
        return $this->utilisateurRepository->lastUser();
    }

}
<?php

namespace App\Services;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Repository\ReponseRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints as Assert;

class ContactService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var ContactRepository
     */
    private $contactRepository;

    public function __construct(
        ContactRepository $contactRepository,
        EntityManagerInterface $em,
        Security $security,
        ReponseRepository $reponseRepository
    ){
        $this->em = $em;
        $this->security = $security;
        $this->contactRepository = $contactRepository;
        $this->reponseRepository   = $reponseRepository;
    }

    #region Contact Existe : Bool
    /**
     * Vérifie l'Existence d'un Contact via Id
     *
     * @param $id
     *
     * @return bool
     */
    public function exist($id): bool
    {
        return [] != $this->contactRepository->findBy(['id'=>$id]);
    }
    #endregion Contact Existe

    #region Contact Existent : Bool
    /**
     * Vérifie que le tableau Contact ne soit pas vide
     *
     * @return bool
     */
    public function Exists(): bool
    {
        return [] != $this->contactRepository->findAll();
    }
    #endregion Contact Existent

    #region Recuperer un Contact
    /**
     * Retourne un Contact
     *
     * @param int $id
     *
     * @return Contact
     */
    public function recupeContact(int $id): Contact
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Ce Contact n'existe pas !");
        }
        return $this->contactRepository->findOneBy(['id'=>$id]);
    }
    #endregion Contact Existe : Bool

    #region pagination
    /**
     * Pour la pagination des Contact
     *
     * @return Query
     */
    public function pagination(): Query
    {
        return $this->contactRepository->findContactPaginatorQuery();
    }
    #endregion

    /**
     * @param string      $param
     * @param Contact $contact
     *
     * @return Contact
     */
    public function verifierUnContact(string $param, Contact $contact): Contact
    {
        if (!isset($param) or $param != 'Contacts'){
            throw new NotFoundHttpException("Ce Parametre n'existe pas !");
        }

        if (!($contact instanceof Contact)){
            throw new NotFoundHttpException("Nous ne sommes pas en présence d'une Instance Contact!");
        }

        return $contact;
    }


    #region Recuperer Toutes les Contact
    /**
     * Retourne tous les Contacts
     *
     * @return array
     */
    public function allContacts(): array
    {
        if (!$this->Exists()){
            throw new NotFoundHttpException("Ces Contacts n'existent pas !");
        }
        return $this->contactRepository->findAll();
    }
    #endregion Contact

    #region Contact Initialisation
    /**
     *
     * @return Contact
     */
    public function initialiserContact(): Contact
    {

        return  (new Contact())
            ->setNom('')
            ->setPrenom('')
            ->setMail('')
            ->setText('')
            ->setCreateAt(new DateTime('now'))
            ->setAfficher(false)
            ;
    }
    #endregion

    #region Contact Initialisation
    /**
     * Ajouter une Contact
     *
     * @param Contact $contact
     *
     * @return Contact
     */
    public function addContact(Contact $contact): Contact
    {
        if (!isset($contact)){
            throw new NotFoundHttpException(" Ce Contact existe déjà !");
        }

        try {
            $contact->setAfficher(1);
            $this->em->persist($contact);
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de la sauvegarde de cet contact" . $exception->getMessage());
        }

     return $contact;
    }
    #endregion

    #region Edit Contact
    /**
     * Edit un contact
     *
     * @param Contact $contact
     *
     * @return Contact
     */
    public function editContacts(Contact $contact): Contact
    {
        if (!isset($contact)){
            throw new NotFoundHttpException("Cet contact n'existe pas !");
        }

        try {

            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de l'enregistrement de ce Contact !" . $exception->getMessage());
        }
        return $contact;
    }
    #endregion Edit

    #region Supprimer une Contact
    /**
     * @param $id
     *
     * @return Contact
     */
    public function deleteContacts($id): Contact
    {
        if (!$this->exist($id)){
            throw new NotFoundHttpException("Ce Contact n'existe pas !");
        }

        try {

            $reponse = $this->reponseRepository->findOneBy(['contact' =>$id]);
            $contact = $this->contactRepository->find($id);

            if ($contact->getId() != 1) {
                if (isset($reponse) and $reponse->getAfficher() == 0){
                    $this->em->remove($reponse);
                }
                $this->em->remove($contact);
                $this->em->flush();
            }else{
                throw new NotFoundHttpException("Vous ne devez pas supprimer ce Contact !");
            }
        } catch (Exception $exception) {
            throw new NotFoundHttpException("Pour Information !" . $exception->getMessage());
        }
        return $contact;
    }
    #endregion

    #region Supprimer un Contact
    /**
     * @param $id
     *
     * @return Contact
     */
    public function deleteContactUser($id): Contact
    {
      return $this->contactRepository->findOneBy(['id'=>$id]);

    }
    #endregion

    #region Supprimer un Contact
    /**
     * @return array
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function ContactUserRecu():array
    {
        return $this->contactRepository->mailRecu();

    }
    #endregion
}
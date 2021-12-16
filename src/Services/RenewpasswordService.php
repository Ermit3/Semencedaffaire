<?php

namespace App\Services;

use App\Entity\Renewpassword;
use App\Notification\ContactNotification;
use App\Repository\RenewPasswordRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class RenewpasswordService
{
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var RenewPasswordRepository
     */
    private $renewPasswordRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var ContactNotification
     */
    private $contactNotification;
    /**
     * @var Security
     */
    private $security;

    public function __construct(
        UtilisateurService $utilisateurService,
        EntityManagerInterface $em,
        RenewPasswordRepository $renewPasswordRepository,
        ContactNotification $contactNotification,
        UserPasswordEncoderInterface $encoder,
        Security $security
    )
   {
       $this->utilisateurService = $utilisateurService;
       $this->em = $em;
       $this->renewPasswordRepository = $renewPasswordRepository;
       $this->encoder = $encoder;
       $this->contactNotification = $contactNotification;
       $this->security = $security;
   }

    #region Controle user
    /**
     * @param string $email
     *
     * @return bool
     */
    public function controlUser(string $email):bool
    {
        return false != $this->utilisateurService->utilisateurExistMail($email);
    }
    #endregion

    /**
     * @param string $email
     *
     * @return Renewpassword
     */
    public function recupeRenew(string $email):Renewpassword
    {
        if (!$this->controlUser($email)){
            throw new NotFoundHttpException("Cette Adresse mail n'existe pas !");
        }
        return $this->renewPasswordRepository->findOneBy(['mail'=>$email]);
    }
    #endregion

    #region InitialiserRenwPassword
    /**
     * @return Renewpassword
     */
    public function InitialiserRenwPassword(): Renewpassword
    {
        return (new Renewpassword())
            ->setMail('')
            ->setNewpassword('Pardefaut')
            ->setAfficher(true)
            ->setCreateAt(new DateTime('now'))
            ;
    }
    #endregion

    #region Renouvelle un RenewPassword
    /**
     * @param Renewpassword $renewpassword
     *
     * @return Renewpassword
     * @throws Exception
     */
    public function renewPassword(Renewpassword $renewpassword):Renewpassword
    {
        if (in_array($renewpassword->getMail(),['stephanebekale@gmail.com','stephanebekale@akobisoft.com','lunabekale@akobisoft.com','lunabekale@akobiwondo.com'])){
            throw new NotFoundHttpException("Vous n'avez pas le droit de modifier ce compte !");
        }

        if (!$renewpassword){
            throw new Exception("Cet Utilisateur n'existe pas !");
        }

        try {
            $Utilisateur = $this->utilisateurService->utilisateurViaMail($renewpassword->getMail());
            $password = $this->encoder->encodePassword($Utilisateur, $renewpassword->getNewpassword());
            $Utilisateur->setPassword($password);
            $this->em->flush();
        }catch (Exception $exception){
            throw new NotFoundHttpException($exception->getMessage());
        }
        return $renewpassword;
    }
    #endregion
    #region Ajouter un RenewPassword
    /**
     * @param Renewpassword $renewpassword
     *
     * @return Renewpassword
     * @throws TransportExceptionInterface
     */
    public function addRenwPassword(Renewpassword $renewpassword): Renewpassword
    {
        if (!$this->controlUser($renewpassword->getMail())){
            throw new NotFoundHttpException("Cet Utilisateur n'existe pas, merci de contacter l'Administrateur !");
        }

        if (in_array($renewpassword->getMail(),['stephanebekale@gmail.com','stephanebekale@akobisoft.com','bizangos@akobisoft.com','akobisoft@gmail.com'])){
            throw new NotFoundHttpException("Vous n'avez pas le droit de modifier ce compte !");
        }

        try {
            $renewpassword->setMail($renewpassword->getMail());
            $this->contactNotification->notifyRenewpwd($renewpassword);
            $this->em->persist($renewpassword);
            $this->em->flush();
        } catch (Exception $exception){
            throw new NotFoundHttpException("Erreur lors de l'enregistrement de votre requête !" . $exception);
        }

        return $renewpassword;
    }
    #endregion

}
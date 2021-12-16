<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Groupe;
use App\Entity\Utilisateur;
use App\Form\RenewpasswordType;
use App\Form\UtilisateurType;
use App\Services\ArrierPlanService;
use App\Services\ContactService;
use App\Services\CotisationService;
use App\Services\CouleurService;
use App\Services\RenewpasswordService;
use App\Services\SlideService;
use App\Services\UtilisateurService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UtilisateurController extends AbstractController
{
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;
    /**
     * var RenewpasswordService
     */
    private $renewpasswordService;
    /**
     * @var ArrierPlanService
     */
    private $arrierPlanService;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var SlideService
     */
    private $slideService;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var CotisationService
     */
    private $cotisationService;
    /**
     * @var \App\Services\ContactService
     */
    private $contactService;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(
        UtilisateurService     $utilisateurService,
        RenewpasswordService   $renewpasswordService,
        ArrierPlanService      $arrierPlanService,
        CouleurService         $couleurService,
        SlideService           $slideService,
        Security               $security,
        CotisationService      $cotisationService,
        ContactService         $contactService,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder
    ){
        $this->utilisateurService   = $utilisateurService;
        $this->arrierPlanService    = $arrierPlanService;
        $this->renewpasswordService = $renewpasswordService;
        $this->couleurService       = $couleurService;
        $this->slideService         = $slideService;
        $this->security             = $security;
        $this->cotisationService    = $cotisationService;
        $this->contactService       = $contactService;
        $this->em                   = $em;
        //asupprimer
        $this->encoder              = $encoder;
    }

    #region Ajouter un Utilisateur

    /**
     * Ajouter un Utilisateur
     *
     * @Route("Essap/Utilisateur/add/{param}/", name="admin.add.util")
     *
     * @param Request  $request
     * @param string   $param
     * @param Security $security
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function ajouterUser(Request $request, string $param, Security $security): Response
    {

        $utilisateur = $this->utilisateurService->initialiserUtilisateur();
        $utilisateur->setSource($security->getUser());
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

//-- Ajouter un Utilisateur
    /*    $groupe = new Groupe();
        $grade = new Grade();

        $utilisateur = new Utilisateur();
        $utilisateur->setNom('BEKALE');
        $utilisateur->setStatut($utilisateur->getParent()."-".($this->utilisateurService->lastUtilisateur()+1));
        $utilisateur->setPrenom('Regis');
        $utilisateur->setLogin('lunabekale@akobisoft.fr');
        $utilisateur->setRoles(['ROLE_SUPER_ADMIN']);
        $utilisateur->setMontant(5000,00);
        $utilisateur->setRoot([ROLE_SUPER_ADMIN]);
        $utilisateur->setStatut(1);
        $utilisateur->setSource($this->getUser());
        $utilisateur->setParent($utilisateur);
        $utilisateur->setAfficher(1);
        $utilisateur->setGroupe($groupe->getId(1));
        $utilisateur->setGrade($grade->getId(1));
        $utilisateur->setMail('stephanebekale@gmail.fr');
        $password = htmlspecialchars(strip_tags('Angeetoile07'));
        $utilisateur->setPassword($this->encoder->encodePassword($utilisateur, $password));
        $this->em->persist($utilisateur);
        $this->em->flush();*/

//--
        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $this->utilisateurService->addUtilisateur($request->request->get('acl_utilisateur')??[],$form->getData());
                $this->addFlash('success',"Féliciations, Merci de completer la \"Cotisation\" de cet Utilisateur!");
                $id          = $this->utilisateurService->lastUtilisateur();
                $cotisations = $this->cotisationService->deleteCotisationsUser($id);
                $last        = $this->utilisateurService->lastUtilisateur();
                return $this->redirectToRoute('Essap',['param'=>'Cotisations', 'id'=>$id, 'Cotisations'=>$cotisations, 'choix'=>'Accueil', 'last' =>$last ]);

            } catch (Exception $exception){
                $this->addFlash('error', $exception->getMessage());
            }
        }

        if ($this->security->getUser()->getRoles() == ['ROLE_ADMIN']) {
            $news              = 'News';
            return $this->render('Nzoe/userBet.html.twig',[
                'form'         => $form->createView(),
                'news'         => $news,
                'Slids'        => $this->slideService->allSlides(),
                'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
                'last'         => $this->utilisateurService->lastUtilisateur(),
                'param'        => $param,
                'couleurs'     => $this->couleurService->couleur(),
                'emails'       => count($this->contactService->ContactUserRecu()),
            ]);
        }

        if ($this->security->getUser()->getRoles() == ['ROLE_SUPER_ADMIN']) {
                $news          = 'News';
            return $this->render('Nzoe/userLa.html.twig',[
                'form'         => $form->createView(),
                'news'         => $news,
                'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
                'Slids'        => $this->slideService->allSlides(),
                'last'         => $this->utilisateurService->lastUtilisateur(),
                'param'        => $param,
                'couleurs'     => $this->couleurService->couleur(),
                'emails'       => count($this->contactService->ContactUserRecu()),
            ]);
        }

    }
    #endregion /Ajouter Utilisateur

    #region Editer un Utilisateur
    /**
     * Modifier un Utilisateur
     *
     * @Route("Essap/Utilisateur/{param}/{id}", name="admin.edit.util", requirements={"id":"\d+"})
     *
     * @param Request     $request
     * @param string      $param
     * @param Utilisateur $utilisateur
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function modifierUser(Request $request, string $param, Utilisateur $utilisateur): Response
    {
        try {
            $this->utilisateurService->verifierUnUser($param, $utilisateur);
        } catch (Exception $exception){
            $this->addFlash('error',$exception->getMessage());
        }
        $utilisateur->setStatut($utilisateur->getStatut());
         $form = $this->createForm(UtilisateurType::class, $utilisateur);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {

             try {
                 $this->utilisateurService->editUtilisateur($request->request->get('acl_utilisateur')??[],$form->getData());
                 $this->addFlash('success','Utilisateur modifié avec succès !');
             } catch (Exception $exception){
                 $this->addFlash('error','Erreur lors de la Modification de cet Utilisateur !' . $exception->getMessage());
             }

         }
         //Utilisateur en Cours

         if ($this->security->getUser()->getRoles() == ['ROLE_USER']) {
             $edit = 'Edit';
             return $this->render('Nzoe/userFo.html.twig',[
                 'form'         => $form->createView(),
                 'edit'         => $edit,
                 'param'        => $param,
                 'choix'        => 'Accueil',
                 'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
                 'last'         => $this->utilisateurService->lastUtilisateur(),
                 'couleurs'     => $this->couleurService->couleur(),
                 'Slids'        => $this->slideService->allSlides(),
                 'emails'       => count($this->contactService->ContactUserRecu()),

             ]);

         }

        if ($this->security->getUser()->getRoles() == ['ROLE_ADMIN']) {
            $edit = 'Edit';
            return $this->render('Nzoe/userBet.html.twig',[
                'form'         => $form->createView(),
                'edit'         => $edit,
                'param'        => $param,
                'choix'        => 'Accueil',
                'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
                'couleurs'     => $this->couleurService->couleur(),
                'Slids'        => $this->slideService->allSlides(),
                'emails'       => count($this->contactService->ContactUserRecu()),

            ]);
        }

         if ($this->security->getUser()->getRoles() == ['ROLE_SUPER_ADMIN']) {
             $edit = 'Edit';
             return $this->render('Nzoe/userLa.html.twig',[
                 'form'         => $form->createView(),
                 'edit'         => $edit,
                 'param'        => $param,
                 'choix'        => 'Accueil',
                 'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
                 'couleurs'     => $this->couleurService->couleur(),
                 'Slids'        => $this->slideService->allSlides(),
                 'emails'       => count($this->contactService->ContactUserRecu()),

             ]);
         }


    }
    #endregion /Editer Utilisateur

    #region Supprimer un Utilisateur
    /**
     * Supprimer un Utilisateur
     *
     * @Route("Essap/Modifier/Utilisateur/{id}", name="admin.delete.util", requirements={"id":"\d+"})
     *
     * @param Utilisateur $utilisateur
     * @param Request     $request
     *
     * @return Response
     */
    public function suppUser(Utilisateur $utilisateur, Request $request): Response
    {
        //  $this->denyAccessUnlessGranted(AkobisoftVoter::DELETE, $utilisateur, "L'utilisateur a tenté d'accéder à une page sans ROLE_SUPER_ADMIN ");

        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->get('_token'))) {
            try {

                $this->utilisateurService->deleteUtilisateur($utilisateur->getId());
                $this->addFlash('success', 'Utilisateur Supprimé avec succès !');
            } catch (Exception $exception){
                $this->addFlash('info', $exception->getMessage());
            }
        }
        return $this->redirectToRoute('Essap',['param'=>'Utilisateurs', 'choix'=>'Accueil']);
    }
    #endregion /Supprimer Utilisateur


    /**
     * @Route("/EffifiEssap/", name="effifiEssape.login")
     * @param Request $request
     *
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function renwspassword(Request $request): Response
    {
        $renewpassword = $this->renewpasswordService->InitialiserRenwPassword();
        $arrpl = $this->arrierPlanService->arrierePlan(1);

        $form = $this->createForm(RenewpasswordType::class, $renewpassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->renewpasswordService->addRenwPassword($form->getData());
                $this->addFlash('success', "Vous avez reçu un mail de renouvellement du password sur : " . $renewpassword->getMail());
            } catch (Exception $exception){
                $this->addFlash('error', $exception->getMessage());
            }

            return $this->redirectToRoute('login',['param'=>'Accueil','choix'=>'Accueil']);
        }

        return $this->render(
            'Nzoe/effifilogin.html.twig',
            [
                'form'     => $form->createView(),
                'arrpl'    => $arrpl,
                'couleurs' => $this->couleurService->couleur(),
                'choix'    =>'Accueil'
            ]
        );
    }

    /**
     * Renouvelle la password
     *
     * @Route("/EffifiEssa/", methods={"GET","POST"}, name="effifiEssaperetour.login")
     *
     * @param Request $request
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */

    public function renwspasswordRep(Request $request): Response
    {
        $renewpassword = $this->renewpasswordService->recupeRenew($request->query->get('mail'));
        $form = $this->createForm(RenewpasswordType::class, $renewpassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->renewpasswordService->renewPassword($form->getData());
                $this->addFlash('success', "Password modifié avec succès, vous pouvez vous connecter à nouveau !");
            }catch (Exception $exception){
                $this->addFlash('error', $exception->getMessage());
            }
            return $this->redirectToRoute('login',['param'=>'Accueil','choix'=>'Accueil']);
        }

        $arrpl = $this->arrierPlanService->arrierePlan(1);
        $retour = $request->query->get('valide');

        return $this->render(
            'Nzoe/effifilogin.html.twig',
            [
                'form'     => $form->createView(),
                'arrpl'    => $arrpl,
                'valide'   => $retour,
                'couleurs' => $this->couleurService->couleur(),
                'emails'   => count($this->contactService->ContactUserRecu()),
            ]
        );
    }

    /**
     * Rechercher un User
     *
     * @Route ("/User/Recherche/SemenceAffaire",name="recherche_User")
     *
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function rechercheUser(Request $request): Response
    {
        //Recuperer le statut
        $statut = $request->get('inputRecherche');

        try {
            $user   = $this->utilisateurService->rechercheUser($statut);
        }catch (Exception $exception){
            $this->addFlash('error',$exception->getMessage());
        }

        $param     = 'Accueil';
        $recherche = 'Ezeng';

       return $this->redirectToRoute('Essap',['param'=>$param,'choix'=>'arborescence','utilisateur'  => $user, 'recherche' => $recherche ]);

    }
}

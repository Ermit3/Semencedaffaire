<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Reponse;
use App\Form\ContactType;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use App\Services\ArrierPlanService;
use App\Services\ContactService;
use App\Services\CouleurService;
use App\Services\PresentationService;
use App\Services\SlideService;
use App\Services\UtilisateurService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ContactController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;
    /**
     * @var PresentationService
     */
    private $presentationService;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var ArrierPlanService
     */
    private $arrierPlanService;
    /**
     * @var ContactService
     */
    private $contactService;
    /**
     * @var \App\Repository\ReponseRepository
     */
    private $reponseRepository;
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;
    /**
     * @var \App\Services\SlideService
     */
    private $slideService;
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;

    /**
     * PresentationController constructor.
     *
     * @param PresentationService                  $presentationService
     * @param Security                             $security
     * @param CouleurService                       $couleurService
     * @param \App\Services\ArrierPlanService      $arrierPlanService
     * @param \App\Services\ContactService         $contactService
     * @param \App\Repository\ReponseRepository    $reponseRepository
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \App\Services\SlideService           $slideService
     */
    public function __construct(
        PresentationService    $presentationService,
        Security               $security,
        CouleurService         $couleurService,
        ArrierPlanService      $arrierPlanService,
        ContactService         $contactService,
        ReponseRepository      $reponseRepository,
        EntityManagerInterface $em,
        SlideService           $slideService,
        UtilisateurService     $utilisateurService
    ){
        $this->security            = $security;
        $this->presentationService = $presentationService;
        $this->couleurService      = $couleurService;
        $this->arrierPlanService   = $arrierPlanService;
        $this->contactService      = $contactService;
        $this->reponseRepository   = $reponseRepository;
        $this->em                  = $em;
        $this->slideService        = $slideService;
        $this->utilisateurService = $utilisateurService;
    }

    /**
     * @Route("/Contact",name="Nsile")
     *
     * @param Request $request
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function contact(Request $request): Response
    {
        try {
            $arrpl = $this->arrierPlanService->arrierePlan(2);
        } catch (Exception $arrierePlanException) {
            $arrpl = $this->arrierPlanService->arrierePlan(1);
            $this->addFlash('error', $arrierePlanException->getMessage().' Contentez vous de celui-ci !');
        }

        $contact = $this->contactService->initialiserContact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*
            if (isset($_POST['g-recaptcha-response'])) {
                $Recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
                $Recaptcha_secret = '6Ldit-MUAAAAABbg3i32R34ugU0KaD5d6qyV3G6Z';
                $Recaptcha_response = $_POST['g-recaptcha-response'];

                $Response = file_get_contents(
                    $Recaptcha_url.'?secret='.$Recaptcha_secret.'&response='.$Recaptcha_response
                );
                $Retoure = json_decode($Response);

                if ($Retoure->success == true && $Retoure->score >= 0.5) {*/

                    try {

                        $contact
                            ->setNom(htmlspecialchars(strip_tags(trim($form->get('nom')->getData()))))
                            ->setPrenom(htmlspecialchars(strip_tags(trim($form->get('prenom')->getData()))))
                            ->setMail(htmlspecialchars(strip_tags(trim($form->get('mail')->getData()))))
                            ->setText(htmlspecialchars(strip_tags(trim($form->get('text')->getData()))))
                            ->setCreateAt(new \DateTime('now'))
                            ->setAfficher(htmlspecialchars(strip_tags(trim(1))));

                        $this->contactService->addContact($contact);
                        $this->addFlash('success','Message reçu, nous allons vous répondre dans les meilleurs délais Merci!');
                        return $this->redirectToRoute('accueil');

                    } catch (Exception $contactException) {

                        $this->addFlash('error', $contactException->getMessage());
                        return $this->redirectToRoute('Nsile');

                    }
          /*      } else {
                    $this->addFlash('error', 'Erreur : Merci de Saisir le Recaptcha!');
                }
            }*/
        }

        return $this->render(
            'pages/Contacts/contact.html.twig',
            [
                'form'        => $form->createView(),
                'couleurs'    => $this->couleurService->couleur(),
                'arrpl'       => $arrpl,
                'emails'      => count($this->contactService->ContactUserRecu()),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
            ]
        );
    }

//Administration  Afficher le Contact

    /**
     * Cette methode permet d'afficher le Contact
     *
     * @Route("Contact/", name="accueil.contact.elere")
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function afficheContact(): Response
    {
        return $this->render('pages/Contacts/contact.html.twig', [
            'couleurs'    => $this->couleurService->couleur(),
            'Present'     => $this->presentationService->allPresent(),
            'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
            'emails'      => count($this->contactService->ContactUserRecu())
        ]);
    }

    /**
     * Pour editer une Presentation
     *
     * @Route("/Essap/edit/Nsile/{param}/{id}", name="admin.edit.contact", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param int     $id
     * @param string  $param
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function editContact(Request $request, int $id, string $param): Response
    {

        if ($param != 'Contacts'){
            $this->addFlash("error","Ce Parametre n'existe pas !");
        }
        $rep = $this->reponseRepository->getReponseById($id);
        $contact = $this->contactService->recupeContact($id);

        $reponse = new Reponse();
        $reponse->setType($rep == []?'Informations':$rep[0]->getType()??$reponse->getType());
        $reponse->setMailsource($rep == []?'e.service@semencedaffaires.com':$rep[0]->getMailsource());
        $reponse->setMaildestination($rep == []?$contact->getMail():$rep[0]->getMaildestination());
        $reponse->setContact($rep == []? $contact :$rep[0]->getContact());
        $reponse->setIdrepondre($rep == []? $contact->getId() :$rep[0]->getIdrepondre());
        $formReponse = $this->createForm(ReponseType::class, $reponse);

        $edit       = 'Edit';
        $editCont   = 'EditContact';
        $reponse    = 'Reponses aux Users';
        $form       = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->contactService->editContacts($form->getData());
                $this->addFlash('success', 'Contact Modifié avec succès !');
            } catch (Exception $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
            return $this->redirectToRoute('admin.edit.contact', [
                'param'       => $param,
                'id'          => $id,
                'Contact'     => $contact,
                'editCont'    => $editCont,
                'couleurs'    => $this->couleurService->couleur(),
                'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
                'emails'      => count($this->contactService->ContactUserRecu()),
                'Slids'       => $this->slideService->allSlides(),
                'reponse'     => $reponse,

            ]);
        }

        if ($this->security->getUser()->getRoles() == ['ROLE_ADMIN']){
            return $this->render(
                'Nzoe/userBet.html.twig',
                [
                    'param'       => $param,
                    'edit'        => $edit,
                    'editCont'    => $editCont,
                    'form'        => $form->createView(),
                    'formRepo'    => $formReponse->createView(),
                    'Contact'     => $contact,
                    'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
                    'couleurs'    => $this->couleurService->couleur(),
                    'emails'      => count($this->contactService->ContactUserRecu()),
                    'reponses'    => $rep,
                    'Slids'       => $this->slideService->allSlides(),
                    'reponse'     => $reponse,
                ]
            );
        }

        if ($this->security->getUser()->getRoles() == ['ROLE_SUPER_ADMIN']){
            return $this->render(
                'Nzoe/userLa.html.twig',
                [
                    'param'       => $param,
                    'edit'        => $edit,
                    'editCont'    => $editCont,
                    'form'        => $form->createView(),
                    'formRepo'    => $formReponse->createView(),
                    'Contact'     => $contact,
                    'couleurs'    => $this->couleurService->couleur(),
                    'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
                    'emails'      => count($this->contactService->ContactUserRecu()),
                    'reponses'    => $rep,
                    'Slids'       => $this->slideService->allSlides(),
                    'reponse'     => $reponse,
                ]
            );
        }

    }

    /**
     * Pour supprimer un Presentation
     *
     * @Route("/Semence/delete/Contact/{id}",name="admin.delete.contact", requirements={"id":"\d+"})
     *
     * @param Request             $request
     * @param Contact             $contact
     * @param                     $id
     *
     * @return Response
     */
    public function deleteContact(Request $request, Contact $contact, $id): Response
    {

        if ($this->isCsrfTokenValid('delete'. $contact->getId(), $request->get('_token'))) {
            try {

                $this->contactService->deleteContacts($id);
                $this->addFlash('success', 'Contact Supprimé avec succès!');
            } catch (Exception $exception) {
                $this->addFlash('warning', $exception->getMessage());
            }
        }
        return $this->redirectToRoute('Essap', ['param'=>'Contacts', 'choix'=>'Accueil']);
    }

    #region Supprimer une Reponse du Contact
    /**
     * Pour supprimer une reponse il faut prendre cette route
     * @Route("/Supprime/Reponse/{id}", name="admin.reponse.delete")
     *
     * @param Request $request
     * @param Reponse $reponse
     *
     * @return Response
     */
    public function deletReponse(Request $request, Reponse $reponse): Response
    {
        $idc = $request->get('idc');
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->get('_token'))) {
            $reponse->setAfficher(0);
            $this->em->flush();
            $this->addFlash('success', 'Réponse : Supprimée avec succès !');
        }

        return $this->redirectToRoute('admin.edit.contact', ['id' => $idc, 'param'=>'Contacts']);
    }
    #endregion

    /**
     * Ajouter une reponse
     * @Route("/add/Reponse", name="admin.add.reponse")
     *
     * @param Request             $request
     *
     * @return Response
     */
    public function addReponse(Request $request): Response
    {
        $reponse    = $request->request->get('reponse');

        $contact    = $this->contactService->recupeContact((integer)$reponse['contact']);
        $NewReponse = new Reponse();
        $NewReponse->setType($reponse['type']);
        $NewReponse->setText($reponse['text']);
        $NewReponse->setSource($this->getUser());
        $NewReponse->setContact($contact);
        $NewReponse->setAfficher(1);
        $NewReponse->setCreateAt(new \DateTime('now'));
        $NewReponse->setMailsource($reponse['mailsource']);
        $NewReponse->setMaildestination($reponse['maildestination']);
        $NewReponse->setIdrepondre((integer)$reponse['idrepondre']);

        $this->em->persist($NewReponse);
        $this->em->flush();

        $this->addFlash('success', 'Reponse ajoutée avec succèss !');
        return $this->redirectToRoute('admin.edit.contact',['param'=>'Contacts', 'id'=>$contact->getId()]);


    }
}
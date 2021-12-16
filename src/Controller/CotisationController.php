<?php

namespace App\Controller;

use App\Entity\Cotisations;
use App\Entity\Utilisateur;
use App\Form\CotisationType;
use App\Services\ContactService;
use App\Services\CotisationService;
use App\Services\CouleurService;
use App\Services\SlideService;
use App\Services\UtilisateurService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CotisationController extends AbstractController
{
    /**
     * @var CotisationService
     */
    private $cotisationService;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var SlideService
     */
    private $slideService;
    /**
     * @var ContactService
     */
    private $contactService;
    /**
     * @var Utilisateur
     */
    private $utilisateur;
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;

    public function __construct(
        CotisationService $cotisationService,
        Security          $security,
        CouleurService    $couleurService,
        SlideService      $slideService,
        ContactService    $contactService,
        UtilisateurService $utilisateurService
    ){
        $this->cotisationService = $cotisationService;
        $this->security          = $security;
        $this->couleurService    = $couleurService;
        $this->slideService      = $slideService;
        $this->contactService    = $contactService;
        $this->utilisateurService = $utilisateurService;
    }

    #region Ajouter une Cotisation

    /**
     * Ajouter un Cotisation
     *
     * @Route("Essap/Cotisations/add/", name="admin.add.cotis")
     *
     * @param Request $request
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function ajouterCotis(Request $request): Response
    {
        $cotisations = $this->cotisationService->initialiserCotisations();
        $cotisations->setSource($this->security->getUser());
        $form        = $this->createForm(CotisationType::class, $cotisations);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->cotisationService->addCotisations($form->getData());
                $this->addFlash('success','Cotisations Ajouté avec succès !');
            } catch (Exception $exception){
                $this->addFlash('error','Erreur lors de la Modification de cette Cotisations !' . $exception->getMessage());
            }
            return $this->redirectToRoute('Essap',['param'=>'Cotisations', 'choix'=>'Accueil']);
        }

             $param        = 'Cotisations';
             $news         = 'News';
             return $this->render('Nzoe/userLa.html.twig',[
            'form'         => $form->createView(),
            'news'         => $news,
            'cotisations'  => $cotisations,
            'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
            'param'        => $param,
            'couleurs'     => $this->couleurService->couleur(),
            'emails'       => count($this->contactService->ContactUserRecu()),
        ]);
    }
    #endregion /Ajouter Cotisations

    #region Editer une Cotisations
    /**
     * Modifier une Cotisations
     *
     * @Route("Essap/Cotisations/{param}/{id}", name="admin.edit.cotis", requirements={"id":"\d+"})
     *
     * @param Request     $request
     * @param string      $param
     * @param Cotisations $cotisations
     * @param int         $id
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function modifierCotis(Request $request, string $param, Cotisations $cotisations, int $id): Response
    {
        try {
            $this->cotisationService->verifierUnCotisations($param, $cotisations);
        } catch (Exception $exception){
            $this->addFlash('error',$exception->getMessage());
        }

        $form = $this->createForm(CotisationType::class, $cotisations);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->cotisationService->editCotisations($form->getData());
                $this->addFlash('success','Cotisations modifiée avec succès !');
            } catch (Exception $exception){
                $this->addFlash('error','Erreur lors de la Modification de cette Cotisations !' . $exception->getMessage());
            }
        }

        if ($this->security->getUser()->getRoles() == ['ROLE_USER']) {
            $edit = 'Edit';
            return $this->render('Nzoe/userFo.html.twig',[
                'form'         => $form->createView(),
                'edit'         => $edit,
                'param'        => $param,
                'choix'        => 'Accueil',
                'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                'Cotisations'  => $cotisations,
                'couleurs'     => $this->couleurService->couleur(),
                'Slids'        => $this->slideService->allSlides(),
                'emails'       => count($this->contactService->ContactUserRecu()),

            ]);

        }

        if ($this->security->getUser()->getRoles() == ['ROLE_ADMIN']) {
            $edit = 'Edit';
            return $this->render(
                'Nzoe/userBet.html.twig',
                [
                    'form'        => $form->createView(),
                    'edit'        => $edit,
                    'param'       => $param,
                    'utilisateur' => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                    'cotisations' => $cotisations,
                    'couleurs'    => $this->couleurService->couleur(),
                    'Slids'       => $this->slideService->allSlides(),
                    'emails'      => count($this->contactService->ContactUserRecu()),
                ]
            );
        }

        if ($this->security->getUser()->getRoles() == ['ROLE_SUPER_ADMIN']) {
            $edit = 'Edit';
            return $this->render(
                'Nzoe/userLa.html.twig',
                [
                    'form'         => $form->createView(),
                    'edit'         => $edit,
                    'param'        => $param,
                    'cotisations'  => $cotisations,
                    'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
                    'couleurs'     => $this->couleurService->couleur(),
                    'Slids'        => $this->slideService->allSlides(),
                    'emails'       => count($this->contactService->ContactUserRecu()),
                ]
            );
        }
    }
    #endregion /Editer Cotisations

    #region Supprimer une Cotisations
    /**
     * Supprimer une Cotisations
     *
     * @Route("Essap/Modifier/Cotisations/{id}", name="admin.delete.cotis", requirements={"id":"\d+"})
     *
     * @param Cotisations $cotisations
     * @param Request     $request
     *
     * @return Response
     */
    public function suppCotis(Cotisations $cotisations, Request $request): Response
    {
        //  $this->denyAccessUnlessGranted(AkobisoftVoter::DELETE, $utilisateur, "L'utilisateur a tenté d'accéder à une page sans ROLE_SUPER_ADMIN ");

        if ($this->isCsrfTokenValid('delete'.$cotisations->getId(), $request->get('_token'))) {
            try {
                $this->cotisationService->deleteCotisations($cotisations->getId());
                $this->addFlash('success', 'Cotisations Supprimée avec succès !');
            } catch (Exception $exception){
                $this->addFlash('info', $exception->getMessage());
            }
        }
        return $this->redirectToRoute('Essap',['param'=>'Cotisations', 'choix'=>'Accueil']);
    }
    #endregion /Supprimer Cotisations
}

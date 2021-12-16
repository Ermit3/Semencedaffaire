<?php

namespace App\Controller;

use App\Entity\Cotisations;
use App\Entity\Couleur;
use App\Form\CotisationType;
use App\Form\CouleurType;
use App\Services\ContactService;
use App\Services\CotisationService;
use App\Services\CouleurService;
use App\Services\SlideService;
use App\Services\UtilisateurService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CouleurController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ContactService
     */
    private $contactService;
    /**
     * @var UtilisateurService
     */
    private $utilisateurService;
    /**
     * @var SlideService
     */
    private $slideService;

    public function __construct(
        CouleurService $couleurService,
        Security $security,
        EntityManagerInterface $em,
        ContactService $contactService,
        UtilisateurService $utilisateurService,
        SlideService        $slideService
    ){
        $this->security = $security;
        $this->couleurService = $couleurService;
        $this->em = $em;
        $this->contactService = $contactService;
        $this->utilisateurService = $utilisateurService;
        $this->slideService = $slideService;
    }

    #region Ajouter une Couleur

    /**
     * Ajouter une Couleur
     *
     * @Route("Essap/Couleur/add/", name="admin.add.couleur")
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function ajouterCouleur(Request $request): Response
    {
        $couleur = $this->couleurService->initialiserCouleurs();
        $form = $this->createForm(CouleurType::class, $couleur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->couleurService->addCouleur($form->getData());
                $this->addFlash('success','Couleur Ajouté avec succès !');
            } catch (Exception $exception){
                $this->addFlash('error','Erreur lors de la Modification de cette Couleur !' . $exception->getMessage());
            }
            return $this->redirectToRoute('Essap',['param'=>'Couleurs', 'choix'=>'Accueil']);
        }

             $param        = 'Couleurs';
             $news         = 'News';
             $coul         = 'Couleur';
             return $this->render('Nzoe/userLa.html.twig',[
            'form'         => $form->createView(),
            'news'         => $news,
            'couleur'      => $couleur,
            'param'        => $param,
            'coul'         => $coul,
            'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()?$this->security->getUser()->getId():''),
            'couleurs'     => $this->couleurService->couleur(),
            'emails'       => count($this->contactService->ContactUserRecu()),
            'Slids'        => $this->slideService->allSlides(),
        ]);
    }
    #endregion /Ajouter Couleur

    #region Editer une Couleur
    /**
     * Modifier une Couleur
     *
     * @Route("Essap/Couleurs/{param}/{id}", name="admin.edit.couleur", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param string  $param
     * @param Couleur $couleur
     * @param int     $id
     *
     * @return Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function modifierColeur(Request $request, string $param, Couleur $couleur, int $id): Response
    {
        try {
            $this->couleurService->verifierUnCouleur($param, $couleur);
        } catch (Exception $exception){
            $this->addFlash('error',$exception->getMessage());
        }

        $form = $this->createForm(CouleurType::class, $couleur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->couleurService->editCouleur($form->getData());
                $this->addFlash('success','Couleur modifiée avec succès !');
            } catch (Exception $exception){
                $this->addFlash('error','Erreur lors de la Modification de cette Couleur !' . $exception->getMessage());
            }
        }

        $edit = 'Edit';
        $coul = 'Couleur';
        return $this->render('Nzoe/userLa.html.twig',[
            'form'         => $form->createView(),
            'edit'         => $edit,
            'coul'         => $coul,
            'param'        => $param,
            'utilisateur'  => $this->utilisateurService->recupeUtilisateure($this->security->getUser()->getId()),
            'couleurs'     => $this->couleurService->couleur(),
            'emails'       => count($this->contactService->ContactUserRecu()),
            'Slids'        => $this->slideService->allSlides(),
        ]);

    }
    #endregion /Editer Couleur

    #region Supprimer une Couleur
    /**
     * Supprimer une Couleur
     *
     * @Route("Essap/Modifier/Couleur/{id}", name="admin.delete.couleur", requirements={"id":"\d+"})
     *
     * @param Couleur $couleur
     * @param Request     $request
     *
     * @return Response
     */
    public function suppCotis(Couleur $couleur, Request $request): Response
    {
        //  $this->denyAccessUnlessGranted(AkobisoftVoter::DELETE, $utilisateur, "L'utilisateur a tenté d'accéder à une page sans ROLE_SUPER_ADMIN ");

        if ($this->isCsrfTokenValid('delete'.$couleur->getId(), $request->get('_token'))) {
            try {
                $this->couleurService->deleteCouleur($couleur->getId());
                $this->addFlash('success', 'Couleur Supprimée avec succès !');
            } catch (Exception $exception){
                $this->addFlash('info', $exception->getMessage());
            }
        }
        return $this->redirectToRoute('Essap',['param'=>'Couleurs', 'choix'=>'Accueil']);
    }
    #endregion /Supprimer Couleur

    #region Editer une Couleur
    /**
     * Modifier une Couleur
     *
     * @Route("Essap/Couleurs/Choix/{param}/{id}", name="admin.edit.effet", requirements={"id":"\d+"})
     *
     * @param Request     $request
     * @param string      $param
     * @param Couleur     $couleur
     * @param int         $id
     *
     * @return Response
     */
    public function ChoixColeur(Request $request, string $param, Couleur $couleur, int $id): Response
    {
        try {
            $this->couleurService->verifierUnCouleur($param, $couleur);
        } catch (Exception $exception){
            $this->addFlash('error',$exception->getMessage());
        }

            try {
                $bd = $this->em->createQueryBuilder();
                $qr = $bd->update('App\Entity\Couleur', 'c')
                    ->set('c.afficher','?1')
                    ->setParameter(1,0)
                    ->getQuery();
                    $qr->execute();

                $this->couleurService->effetCouleur($couleur);
                $this->couleurService->effetCouleur($couleur);
                $this->addFlash('success','Couleur modifiée avec succès !');
            } catch (Exception $exception){
                $this->addFlash('error','Erreur lors de la Modification de cette Couleur !' . $exception->getMessage());
            }

        return $this->redirectToRoute('Essap',['param'=>'Couleurs', 'choix'=>'Accueil']);

    }
    #endregion /Editer Couleur
}

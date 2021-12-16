<?php

namespace App\Controller;

use App\Services\ArrierPlanService;
use App\Services\ContactService;
use App\Services\CouleurService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @var CouleurService
     */
    private $couleurService;
    /**
     * @var ContactService
     */
    private $contactService;

    private static $param = 'Accueil';
    private static $choix = 'Accueil';
    /**
     * @var Session
     */
    private $session;

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @param SessionInterface        $session
     * @param CouleurService $couleurService
     * @param ContactService $contactService
     */
   public function __construct(CouleurService $couleurService,ContactService $contactService){
       $this->couleurService = $couleurService;
       $this->contactService = $contactService;
   }

    /**
     * Authentification User
     *
     * @Route("/login/{param}/{choix}", name="login")
     *
     * @param SessionInterface    $session
     * @param AuthenticationUtils $authenticationUtils
     * @param ArrierPlanService   $arrierPlanService
     *
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function autentification(
        SessionInterface $session,
        AuthenticationUtils $authenticationUtils,
        ArrierPlanService $arrierPlanService): Response
    {
        
        $error    = $authenticationUtils->getLastAuthenticationError();
        $lastUser = $authenticationUtils->getLastUsername();
        $arrPlan  = $arrierPlanService->arrierePlan(1);

        return $this->render('security/login.html.twig', [
            'dernier_user' => $lastUser,
            'error'        => $error,
            'arrpl'        => $arrPlan,
            'couleurs'     => $this->couleurService->couleur(),
            'choix'        => self::$param,
            'param'        => self::$choix,
            'emails'       => count($this->contactService->ContactUserRecu()),
        ]);
    }
}

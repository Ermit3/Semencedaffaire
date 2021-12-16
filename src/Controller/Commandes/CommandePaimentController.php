<?php

namespace App\Controller\Commandes;

use App\Entity\CommandeClient;
use App\Exception\ArrierePlanException;
use App\Repository\CommandeClientRepository;
use App\Services\ArrierePlanService;
use App\Stripe\StripeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandePaimentController extends AbstractController
{



    /**
     * @Route("/Commande/paiement/{id}", name="commande_epie_miang")
     * @IsGranted ("IS_AUTHENTICATED_ANONYMOUSLY")
     * @param                          $id
     * @param CommandeClientRepository $commandeClientRepository
     * @param StripeService            $stripeService
     *
     * @return RedirectResponse|Response
     * @throws ArrierePlanException
     */
    public function showCarteForm($id, CommandeClientRepository $commandeClientRepository, StripeService $stripeService)
    {
        $commande = $commandeClientRepository->find($id);

        if(
            !$commande ||
            ($commande && $commande->getUtilisateur() !== $this->getUser()) ||
            ($commande && $commande->getStatutcom() === CommandeClient::STATUS_PAID)
        ) {
            return $this->redirectToRoute('Commande_Panier');
        }

        try {
            $arrpl = $this->arrierePlanService->findArrirePlan(12);
        } catch (ArrierePlanException $arrierePlanExcept) {
            $arrpl = $this->arrierePlanService->findArrirePlan(1);

            $this->addFlash('error', $arrierePlanExcept->getMessage().' Contentez-vous de Celui-ci !');
        }

        $intent = $stripeService->getPaimentIntent($commande);

        return $this->render('Nzoes/Commandes/paiment.html.twig',[
            'clientSecret'    => $intent->client_secret,
            'commande'        => $commande,
            'stripePublicKey' =>$stripeService->getPublickey(),
            'arrpl'           => $arrpl
       ]);
    }
}
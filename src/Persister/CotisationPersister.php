<?php

namespace App\Persister;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CotisationPersister extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function CotisationSave(Utilisateur $user)
    {
        $user->setMontant(200000);

        $this->em->persist($user);
    }
}

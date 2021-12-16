<?php

namespace App\Persister;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmpreintePersister extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function EmpreinteSave(Utilisateur $parent, Utilisateur $child)
    {
        #Si l'empreinte est null, une neuve est créé
        if (is_null($parent->getEmpreinte())) {
            $parent->setEmpreinte([]);
            $this->em->persist($parent);
        }
        #Récupération et compte de l'empreinte du parent maximum 3
        $parent_empreinte = $parent->getEmpreinte();
        $parent_id = $parent->getId();
        $p_count = count($parent_empreinte);
        if ($p_count > 3) {
            $p_count = count($parent_empreinte) - 1;
        }

        #Si l'empreinte est null, une neuve est créé
        if (is_null($child->getEmpreinte())) {
            $child->setEmpreinte([]);
        }
        $child_empreinte = $child->getEmpreinte();

        #Vérifie que le parent n'est pas dans l'empreinte pas dans de l'enfant & que l'enfant n'est pas dans l'empreinte du parent
        if (!in_array($parent_id, $child->getEmpreinte()) && !in_array($child->getId(), $parent->getEmpreinte())) {
            foreach (array_slice($parent_empreinte, -$p_count) as $value) {
                $child_empreinte[] = $value;
            }
            $child_empreinte[] = $parent_id;
            $child->setEmpreinte($child_empreinte);
            dump('YES');

            $this->em->persist($child);
            $this->em->flush();
        } else {
            dump('NON');
        }
    }
}

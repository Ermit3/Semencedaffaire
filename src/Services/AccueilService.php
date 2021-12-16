<?php

namespace App\Services;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\Criteria;

/**
 * Class AccueilService
 *
 * Fournisseur de services dédiés à la Gestion de l'Accueil
 *
 * @package App\Services
 */
class AccueilService
{

    /**
     * @var UtilisateurRepository
     */
    protected $utilisateurRepository;

    protected $data;

    public function __construct(UtilisateurRepository $utilisateurRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
    }

    public function FunctionName($utilisateur)
    {
        foreach ($this->data['ids'] as $key => $value) {
            $user = $this->utilisateurRepository->findOneBy(['id' => $value]);

            foreach ($user->getChildren()->getValues() as $key => $value) {
                $this->data['ids'][] = $user->getId();
                $this->data['user'][] = $user;
                $this->data['Z2'][] = $user->getId();
            }
        }

        if (count($this->data['ids']) < 15) {
            foreach ($this->data['ids'] as $key => $value) {
                $user = $this->utilisateurRepository->findOneBy(['id' => $value]);

                foreach ($user->getChildren()->getValues() as $key => $value) {
                    $this->data['ids'][] = $user->getId();
                    $this->data['user'][] = $user;
                    $this->data['Z3'][] = $user->getId();
                }
            }
        }

        return $this->data;
    }

    public function Comptage($utilisateur)
    {
        $this->data = ['parent' => $utilisateur, 'ids' => [], 'user' => []];

        $children = $utilisateur->getChildren()->getValues();

        foreach ($children as $value) {
            $this->data['ids'][] = $value->getId();
            $this->data['user'][] = $value;
            $this->data['Z1'][] = $value->getId();
        }

        $this->FunctionName($utilisateur);

        return $this->data;
    }

    public function FindChildren($id)
    {
        $result = [];
        foreach ($this->utilisateurRepository->findAll() as $user) {
            if (!is_null($user->getEmpreinte()) && in_array($id, $user->getEmpreinte())) {
                $result[] = $user;
            }
        }
        return $result;
    }
}

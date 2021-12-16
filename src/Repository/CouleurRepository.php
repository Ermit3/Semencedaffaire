<?php

namespace App\Repository;

use App\Entity\Couleur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Couleur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Couleur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Couleur[]    findAll()
 * @method Couleur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CouleurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Couleur::class);
    }

    /**
     * @return Query
     */
    public function findCouleurPaginatorQuery(): Query
    {
        return $this->findAllUtilpagination()
            ->getQuery();
    }

    private function findAllUtilpagination(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nom != :val')
            ->setParameter('val','')
            ;
    }

    /**
     * @return Couleur
     */
    public function choixCouleur(): Couleur
    {
        return $this->findOneBy(['afficher'=>1]);
    }
}

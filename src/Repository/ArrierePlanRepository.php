<?php

namespace App\Repository;

use App\Entity\ArrierePlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArrierePlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArrierePlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArrierePlan[]    findAll()
 * @method ArrierePlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArrierePlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArrierePlan::class);
    }

    /**
     * @return Query
     */
    public function findArrPlPaginatorQuery(): Query
    {
        return $this->findAllArrPlpagination()
            ->getQuery();
    }

    private function findAllArrPlpagination(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nom != :val')
            ->andWhere('a.filename!= :img')
            ->setParameter('val','')
            ->setParameter('img','')
            ;
    }
}

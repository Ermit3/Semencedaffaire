<?php

namespace App\Repository;

use App\Entity\Presentation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Presentation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Presentation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Presentation[]    findAll()
 * @method Presentation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PresentationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Presentation::class);
    }

    /**
     * @return Query
     */
    public function findPresentPaginatorQuery(): Query
    {
        return $this->findAllPresentpagination()
            ->getQuery();
    }

    private function findAllPresentpagination(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.filename != :val')
            ->andWhere('a.afficher = :affich')
            ->setParameter('val','')
            ->setParameter('affich',1)
            ;
    }
}

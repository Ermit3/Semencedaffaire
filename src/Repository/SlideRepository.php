<?php

namespace App\Repository;

use App\Entity\Slide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Slide|null find($id, $lockMode = null, $lockVersion = null)
 * @method Slide|null findOneBy(array $criteria, array $orderBy = null)
 * @method Slide[]    findAll()
 * @method Slide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SlideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Slide::class);
    }

    /**
     * @return Query
     */
    public function findSlidePaginatorQuery(): Query
    {
        return $this->findAllSlidepagination()
            ->getQuery();
    }

    private function findAllSlidepagination(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.filename != :val')
            ->andWhere('a.afficher = :affich')
            ->setParameter('val','')
            ->setParameter('affich',1)
            ;
    }
}

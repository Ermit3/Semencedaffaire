<?php

namespace App\Repository;

use App\Entity\NewsLetter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NewsLetter|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsLetter|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsLetter[]    findAll()
 * @method NewsLetter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsLetterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsLetter::class);
    }


    /**
     * @return Query
     */
    public function findNewsLetterPaginatorQuery(): Query
    {
        return $this->findAllNewsLetPagination()
            ->getQuery();
    }

    private function findAllNewsLetPagination(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nom != :val')
            ->andWhere('a.mail != :mail')
            ->setParameter('val','')
            ->setParameter('mail','')
            ;
    }
}

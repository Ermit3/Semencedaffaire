<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Query
     */
    public function findArticlPaginatorQuery(): Query
    {
        return $this->findAllArticlpagination()
            ->getQuery();
    }

    private function findAllArticlpagination(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.titre != :val')
            ->andWhere('a.filename!= :img')
            ->setParameter('val','')
            ->setParameter('img','')
            ;
    }
}

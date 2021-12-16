<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    /**
     * @return Query
     */
    public function findProduitPaginatorQuery(): Query
    {
        return $this->findAllProduitpagination()
            ->getQuery();
    }

    private function findAllProduitpagination(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.titre != :val')
            ->andWhere('a.filenameface!= :img')
            ->setParameter('val','')
            ->setParameter('img','')
            ;
    }
}

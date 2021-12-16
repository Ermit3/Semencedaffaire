<?php

namespace App\Repository;

use App\Entity\Cotisations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cotisations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cotisations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cotisations[]    findAll()
 * @method Cotisations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CotisationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cotisations::class);
    }

    /**
     * @return Query
     */
    public function findCotisPaginatorQuery(): Query
    {
        return $this->findAllCotispagination()
            ->getQuery();
    }

    private function findAllCotispagination(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.utilisateur != :val')
            ->andWhere('c.afficher = :afficher')
            ->setParameter('val',0)
            ->setParameter('afficher',1)
            ;
    }

}

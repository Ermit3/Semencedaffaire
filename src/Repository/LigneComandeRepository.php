<?php

namespace App\Repository;

use App\Entity\LigneComande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneComande|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneComande|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneComande[]    findAll()
 * @method LigneComande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneComandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneComande::class);
    }


    /**
     * @return Query
     */
    public function findLigneComandePaginatorQuery(): Query
    {
        return $this->findAllLigneComandepagination()
            ->getQuery();
    }

    /**
     * @return QueryBuilder
     */
    private function findAllLigneComandepagination(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.produit is not null')
            ->andWhere('a.quantite is not null')
            ;
    }

    public function findLignComByUser(int $int){
        return $this->createQueryBuilder('l')
            ->join('App\Entity\CommandeClient','c','with','c.id = l.commande','')
            ->andWhere('c.utilisateur = :user')
            ->setParameter('user',$int)
            ->getQuery()
            ->getResult();
    }
}

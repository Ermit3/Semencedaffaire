<?php

namespace App\Repository;

use App\Entity\CommandeClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommandeClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeClient[]    findAll()
 * @method CommandeClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeClient::class);
    }

    /**
     * @param $mail
     * @param $reference
     * @return CommandeClient[]
     */
    public function getverifCommande($mail, $reference): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.mail, c.reference')
            ->andWhere('c.mail = :mail')
            ->andWhere('c.reference = :reference')
            ->setParameters(array('mail' => $mail, 'reference'=> $reference))
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Query
     */
    public function findCommandePaginatorQuery(): Query
    {
        return $this->findAllCommandepagination()
            ->getQuery();
    }

    private function findAllCommandepagination(): QueryBuilder
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.fullName != :val')
            ->andWhere('s.total != :total')
            ->setParameter('val','')
            ->setParameter('total',0)
            ;
    }

    /*
    public function findOneBySomeField($value): ?CommandeClient
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

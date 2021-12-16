<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function mailRecu():array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.afficher = :val')
            ->setParameter('val', 1)
            ->getQuery()
            ->getScalarResult();
    }
    /**
     * @return Query
     */
    public function findContactPaginatorQuery(): Query
    {
        return $this->findAllContactpagination()
            ->getQuery();
    }

    private function findAllContactpagination(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nom != :val')
            ->andWhere('a.mail != :mail')
            ->setParameter('val','')
            ->setParameter('mail','')
            ;
    }
}

<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends NestedTreeRepository implements ServiceEntityRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Utilisateur::class));
    }

    /**
     * @return Query
     */
    public function findUtilPaginatorQuery(): Query
    {
        return $this->findAllUtilpagination()
            ->getQuery();
    }

    private function findAllUtilpagination(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nom != :val')
            ->andWhere('a.mail!= :mail')
            ->setParameter('val','')
            ->setParameter('mail','')
            ;
    }

    /**
     * @param int $id
     *
     * @return Utilisateur
     */
    public function findRoleUseryId(int $id): Utilisateur
    {
        return $this->createQueryBuilder('u')
            ->select('u.roles')
            ->andWhere('u.id != :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function nbrUser(): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->getQuery()
            ->getSingleScalarResult();

    }

    /**
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function lastUser(): int
    {
        return $this->createQueryBuilder('u')
            ->select('u.id')
            ->orderBy('u.id','DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();

    }
}

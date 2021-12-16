<?php

namespace App\Repository;

use App\Entity\RenewPassword;
use App\Entity\Reponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RenewPassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method RenewPassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method RenewPassword[]    findAll()
 * @method RenewPassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RenewPasswordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RenewPassword::class);
    }

    /**
     * @param $email
     *
     * @return int|mixed|string
     */
    public function recupRenewPassword($email)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.mail = :val')
            ->setParameter('val', $email)
            ->getQuery()
            ->getResult();
    }
}

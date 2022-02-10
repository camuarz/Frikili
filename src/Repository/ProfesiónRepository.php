<?php

namespace App\Repository;

use App\Entity\Profesión;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Profesión|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profesión|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profesión[]    findAll()
 * @method Profesión[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfesiónRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profesión::class);
    }

    // /**
    //  * @return Profesión[] Returns an array of Profesión objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Profesión
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

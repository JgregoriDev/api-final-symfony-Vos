<?php

namespace App\Repository;

use App\Entity\Videojoc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Videojoc>
 *
 * @method Videojoc|null find($id, $lockMode = null, $lockVersion = null)
 * @method Videojoc|null findOneBy(array $criteria, array $orderBy = null)
 * @method Videojoc[]    findAll()
 * @method Videojoc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideojocRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Videojoc::class);
    }

    public function save(Videojoc $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Videojoc $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function obtindreJocBuscanElTitol(String $titol)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.titul LIKE :titol')
            ->setParameter('titol', "%$titol%")
            ->getQuery()
            ->getResult();
    }   
    
    public function obtindreQueryJocs()
    {
        return $this->createQueryBuilder('v')
        ->getQuery();
    }

    public function filterByPrice($preuMin,$preuMax)
    {

        return $this->createQueryBuilder("v")->where('v.preu BETWEEN :dmin AND :dmax')
            ->setParameter('dmin', $preuMin)
            ->setParameter('dmax', $preuMax)
            ->getQuery()
            ->execute();

    }

//    /**
//     * @return Videojoc[] Returns an array of Videojoc objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Videojoc
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use Doctrine\ORM\Query;
use App\Entity\Propertys;
use App\Entity\PropertySearch;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Propertys>
 *
 * @method Propertys|null find($id, $lockMode = null, $lockVersion = null)
 * @method Propertys|null findOneBy(array $criteria, array $orderBy = null)
 * @method Propertys[]    findAll()
 * @method Propertys[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Propertys::class);
    }

    public function add(Propertys $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Propertys $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Query[]
     */
    public function findAllVisibleQuery(PropertySearch $search): Query
    {
        $query =  $this->findVisibleQuery();
        if ($search->getMaxPrice()) {
            $query = $query
            ->andWhere('p.price <= :maxprice')
            ->setParameter('maxprice', $search->getMaxPrice());
        }

        if ($search->getMinSurface()) {
            $query = $query
            ->andWhere('p.surface >= :minsurface')
            ->setParameter('minsurface', $search->getMinSurface());
        }

            if ($search->getOptions()->count() > 0) {
                $k = 0;
                foreach($search->getOptions() as $option) {
                    $k++;
                    $query = $query
                        ->andWhere(":option$k MEMBER OF p.options")
                        ->setParameters("option$k", $option);
                }
            }

            return $query->getQuery();
    }

    /**
     * @return Propertys[]
     */
    public function findLatest(): array 
    {
        return $this->findVisibleQuery()
        ->setMaxResults(4)
        ->getQuery()
        ->getResult();
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.sold = false');
    }

//    /**
//     * @return Propertys[] Returns an array of Propertys objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Propertys
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

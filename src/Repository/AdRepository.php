<?php

namespace App\Repository;

use App\Entity\Ad;
use App\Entity\AdSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    public function findAllOrderByNew()
    {
        return $this->createQueryBuilder('ad')
            ->andWhere('ad.createdAt IS NOT NULL')
            ->orderBy('ad.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByTag($value)
    {
        return $this->createQueryBuilder('ad')
            ->innerJoin('ad.tags', 'tag')
            ->addSelect('tag')
            ->andWhere('tag.title LIKE :val OR ad.title LIKE :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    public function findBySearchBar(AdSearch $search)
    {
        $query = $this->createQueryBuilder('ad')
                      ->where('ad.createdAt IS NOT NULL')
                      ->orderBy('ad.createdAt', 'DESC');

        if($search->getQ()){
            $query = $query
                ->andWhere("CONCAT(ad.title,' ',ad.description) LIKE :q")
                ->setParameter('q', "%{$search->getQ()}%");
        }

        if($search->getTags()->count() > 0){
            $i = 0;
            foreach ($search->getTags() as $i => $tag){
                $i++;
                $query = $query
                    ->andWhere(":tag$i MEMBER OF ad.tags")
                    ->setParameter("tag$i", $tag);
            }
        }

        if($search->getMaxPrice()){
            $query = $query
                ->andWhere('ad.price <= :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Ad[] Returns an array of Ad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ad
    {
        return $this->createQueryBuilder('ad')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

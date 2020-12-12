<?php

namespace App\Repository;

use App\Entity\Annonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonces[]    findAll()
 * @method Annonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonces::class);
    }

    /**
     * Recherche les annonces en fonction du formulaire
     * @return void 
     */
    public function search($mots = null, $categorie = null){
        $query = $this->createQueryBuilder('a');
        $query->where('a.active = 1');
        if($mots != null){
            $query->andWhere('MATCH_AGAINST(a.title, a.content) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        if($categorie != null){
            $query->leftJoin('a.categories', 'c');
            $query->andWhere('c.id = :id')
                ->setParameter('id', $categorie);
        }
        return $query->getQuery()->getResult();
    }

    /**
     * Returns number of "Annonces" per day
     * @return void 
     */
    public function countByDate(){
        // $query = $this->createQueryBuilder('a')
        //     ->select('SUBSTRING(a.created_at, 1, 10) as dateAnnonces, COUNT(a) as count')
        //     ->groupBy('dateAnnonces')
        // ;
        // return $query->getQuery()->getResult();
        $query = $this->getEntityManager()->createQuery("
            SELECT SUBSTRING(a.created_at, 1, 10) as dateAnnonces, COUNT(a) as count FROM App\Entity\Annonces a GROUP BY dateAnnonces
        ");
        return $query->getResult();
    }

    /**
     * Returns Annonces between 2 dates
     */
    public function selectInterval($from, $to, $cat = null){
        // $query = $this->getEntityManager()->createQuery("
        //     SELECT a FROM App\Entity\Annonces a WHERE a.created_at > :from AND a.created_at < :to
        // ")
        //     ->setParameter(':from', $from)
        //     ->setParameter(':to', $to)
        // ;
        // return $query->getResult();
        $query = $this->createQueryBuilder('a')
            ->where('a.created_at > :from')
            ->andWhere('a.created_at < :to')
            ->setParameter(':from', $from)
            ->setParameter(':to', $to);
        if($cat != null){
            $query->leftJoin('a.categories', 'c')
                ->andWhere('c.id = :cat')
                ->setParameter(':cat', $cat);
        }
        return $query->getQuery()->getResult();
    }

    /**
     * Returns all Annonces per page
     * @return void 
     */
    public function getPaginatedAnnonces($page, $limit, $filters = null){
        $query = $this->createQueryBuilder('a')
            ->where('a.active = 1');

        // On filtre les données
        if($filters != null){
            $query->andWhere('a.categories IN(:cats)')
                ->setParameter(':cats', array_values($filters));
        }

        $query->orderBy('a.created_at')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
     * Returns number of Annonces
     * @return void 
     */
    public function getTotalAnnonces($filters = null){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.active = 1');
        // On filtre les données
        if($filters != null){
            $query->andWhere('a.categories IN(:cats)')
                ->setParameter(':cats', array_values($filters));
        }

        return $query->getQuery()->getSingleScalarResult();
    }


    // /**
    //  * @return Annonces[] Returns an array of Annonces objects
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
    public function findOneBySomeField($value): ?Annonces
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\Section;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Section|null find($id, $lockMode = null, $lockVersion = null)
 * @method Section|null findOneBy(array $criteria, array $orderBy = null)
 * @method Section[]    findAll()
 * @method Section[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Section::class);
    }

    // /**
    //  * @return Section[] Returns an array of Section objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Section
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function categoriesNum()
    {
        $rawSql = "SELECT COUNT(*)as categoriesNum FROM `section`";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        return $stmt->fetchAll();  
    }



  /**
     * @return Section[] Returns an array of User objects
     */
    public function findAllT()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC')                       
            ->getQuery()
            ->getResult()
        ;
    } 

    public function searchNum($name)
    {
        $rawSql = "SELECT COUNT(*)as searchNum FROM `section` where name like '$name%'";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        return $stmt->fetchAll();  
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findByExampleFieldA($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.name LIKE :val')
            ->setParameter('val', $value.'%')
            ->orderBy('u.name', 'ASC')                       
            ->getQuery()
            ->getResult()
        ;
    }

    // supprimer une section 
    public function deleteSectionA($id)
    {
        $rawSql = "delete FROM `section` where id='$id'";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        
    }       

}

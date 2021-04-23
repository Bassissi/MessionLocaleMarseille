<?php

namespace App\Repository;

use App\Entity\Subsection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Subsection|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subsection|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subsection[]    findAll()
 * @method Subsection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubsectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subsection::class);
    }

    // /**
    //  * @return Subsection[] Returns an array of Subsection objects
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
    public function findOneBySomeField($value): ?Subsection
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
   
    // supprimer toutes les subsections qui attachent avec une section
        public function deleteAllSubAA($id)
        {
            $rawSql = "delete FROM `subsection` where section_id='$id'";
    
            $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
            $stmt->execute([]);
        
            
        }   


   // chercher les ids de subsection  de section 
       
       public function searchidsOfSubsection($id)
       {
           $rawSql = "SELECT id FROM `subsection` WHERE section_id='$id' ";
   
           $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
           $stmt->execute([]);
           return $stmt->fetchAll();
       
           
       }  


    // supprimer subsection
    public function deleteSubPId($id)
    {
        $rawSql = "delete FROM `subsection` where id='$id'";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        
    }   


}

<?php

namespace App\Repository;

use App\Entity\Tchat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Tchat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tchat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tchat[]    findAll()
 * @method Tchat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tchat::class);
    }

    // /**
    //  * @return Tchat[] Returns an array of Tchat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tchat
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function messagesNum()
    {
        $rawSql = "SELECT COUNT(*)as messagesNum FROM `tchat`";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        return $stmt->fetchAll();  
    }

     // supprimer toutes les tchat qui attachent avec une section
     public function deleteAllTchAA($id)
     {
         $rawSql = "delete FROM `tchat` where section_id='$id'";
 
         $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
         $stmt->execute([]);
     
         
     }    
     // supprimer toutes les tchats qui attachent avec une utilisateur
     public function deleteAllTchAU($id)
     {
         $rawSql = "delete FROM `tchat` where user_id='$id'";
 
         $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
         $stmt->execute([]);
     
         
     }         
}

<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
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
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function articlesNum()
    {
        $rawSql = "SELECT COUNT(*)as articlesNum FROM `article`";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        return $stmt->fetchAll();  
    }

    public function searchNum($articlename)
    {
        $rawSql = "SELECT COUNT(*)as searchNum FROM `article` where `name` like '$articlename%'";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        return $stmt->fetchAll();  
    }

    /**
     * @return Article[] Returns an array of User objects
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
  
    /**
     * @return Article[] Returns an array of User objects
     */
    public function findAllT()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.name', 'ASC')                       
            ->getQuery()
            ->getResult()
        ;
    }

    // supprimer un article 
    public function deleteArticleA($id)
    {
        $rawSql = "delete  FROM `article` where id='$id'";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        
    }    

    // supprimer tous les articles qui attachent avec un utilisateur
    public function deleteAllArticlesAU($id)
    {
        $rawSql = "delete  FROM `article` where user_id='$id'";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        
    }   

     // supprimer tous les articles qui attachent avec une subsection
     public function deleteAllArticlesPS($id)
     {
         $rawSql = "delete  FROM `article` where subsection_id='$id'";
 
         $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
         $stmt->execute([]);
     
         
     }   


   // chercher les ids des articles  de subsection
       
   public function searchidsOfArticle($id)
   {
       $rawSql = "SELECT id FROM `article` WHERE subsection_id='$id' ";

       $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
       $stmt->execute([]);
       return $stmt->fetchAll();
   
       
   }  




}

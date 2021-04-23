<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

  // chercher utilisateur par username
    public function findOneByUserNameA($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
  

    public function usersNum()
    {
        $rawSql = "SELECT COUNT(*)as usersNum FROM `user`";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        return $stmt->fetchAll();  
    }
    public function searchNum($username)
    {
        $rawSql = "SELECT COUNT(*)as searchNum FROM `user` where username like '$username%'";

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
            ->andWhere('u.username LIKE :val')
            ->setParameter('val', $value.'%')
            ->orderBy('u.username', 'ASC')                       
            ->getQuery()
            ->getResult()
        ;
    }
  
    /**
     * @return User[] Returns an array of User objects
     */
    public function findAllT()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.username', 'ASC')                       
            ->getQuery()
            ->getResult()
        ;
    }

    // supprimer un utilisateur
    public function deleteUserA($id)
    {
        $rawSql = "delete FROM `user` where id='$id'";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        
    }       
}

<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User; 
use App\Entity\Article;
use App\Entity\Section;
use App\Entity\Subsection;
use App\Entity\Comment;
use App\Entity\Tchat;
use App\Repository\UserRepository; 
use App\Repository\ArticleRepository; 
use App\Repository\SectionRepository; 
use App\Repository\SubsectionRepository; 
use App\Repository\CommentRepository;
use App\Repository\TchatRepository;

use Symfony\Component\HttpFoundation\Request;  
use Doctrine\Common\Persistence\ObjectManager; 
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag; 

// les forms
use App\Form\SectionFormType; 


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class SiteController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
        
        if($session->get('connected')=='true')
        {
           // faire une partie de statistiques et afficher les menue principale de l'administration 

           // usersNum
           $repo=$this->getDoctrine()->getRepository(User::class);            
           $usersNum=$repo->usersNum();
           if($usersNum)
                 $usersNum=$usersNum[0]["usersNum"]; 

            else 
                $usersNum="0"; 


           // articlesNum
           $repo=$this->getDoctrine()->getRepository(Article::class);            
           $articlesNum=$repo->articlesNum();
           if($articlesNum)
                 $articlesNum=$articlesNum[0]["articlesNum"]; 

            else 
                $articlesNum="0"; 

           // categoriesNum
           $repo=$this->getDoctrine()->getRepository(Section::class);            
           $categoriesNum=$repo->categoriesNum();
           if($categoriesNum)
                 $categoriesNum=$categoriesNum[0]["categoriesNum"]; 

            else 
                $categoriesNum="0"; 

           // commentsNum
           $repo=$this->getDoctrine()->getRepository(Comment::class);            
           $commentsNum=$repo->commentsNum();
           if($commentsNum)
                 $commentsNum=$commentsNum[0]["commentsNum"]; 

            else 
                $commentsNum="0";
                 
           // messagesNum
           $repo=$this->getDoctrine()->getRepository(Tchat::class);            
           $messagesNum=$repo->messagesNum();
           if($messagesNum)
                 $messagesNum=$messagesNum[0]["messagesNum"]; 

            else 
                $messagesNum="0"; 


            return $this->render('site/admin.html.twig', [
                'controller_name' => 'SiteController','username'=>$session->get('username'),'usersNum'=>$usersNum,
                'articlesNum'=>$articlesNum, 'categoriesNum'=>$categoriesNum,'commentsNum'=>$commentsNum,
                'messagesNum'=>$messagesNum
            ]);
        }
        else
        {
            return $this->render('site/login.html.twig');     
        }
    }

   /**
     * @Route("/admin/logout", name="logout")
     */
    public function logout()
    {
        $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
        $session->clear(); 
        return $this->render('site/login.html.twig');     
       
    }



    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function login()
    {
      
       $message= 'Verifier votre saisie'; 
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
        if($session->get('connected')=='true')
                {
                    return $this->redirectToRoute('admin');
                }

        if(isset($_POST['validation']))
            {
               
                $repo=$this->getDoctrine()->getRepository(User::class); 
                //findByTitle ("Titre d'article")  find(12) findOneByTitle("Titre d'article n°1")
                $user=$repo->findOneByUsername($_POST['username']);  
                if($user)
                    {
                        $password=sha1($_POST['password']); 
                        if($user->getPassword()==$password&&$user->getUsername()==$_POST['username']&&$user->getAdmin()==1)
                        { 
                           
                            $session = new Session(new NativeSessionStorage(), new AttributeBag());                          
                            $session->set('connected', 'true');
                            $session->set('userId',$user->getId());
                            $session->set('username', $_POST['username'] ); 
                           
                           
                           

                            return $this->redirectToRoute('admin');

                        }                  
                        else
                        {
                             
                            return $this->render('site/login.html.twig',['message'=>              
                                $message]);
                        }
                    }
               else
                    {
                        return $this->render('site/login.html.twig',['message'=>              
                                $message]);
                    }

            }
        else 
            {
               return $this->render('site/login.html.twig',[               
               ]);
            }
    } 
    
    /**
     * @Route("/admin/users", name="users")
     */
     public function users()
     {
        $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
        
        if($session->get('connected')=='true')
        {
           if(isset($_POST["search"]))
           {   
                $repo=$this->getDoctrine()->getRepository(User::class);
                //savoir combien de resulat qu'on a de cet recherche  
                $searchNum=$repo->searchNum($_POST["username"]);
                if($searchNum)
                    $searchNum=$searchNum[0]["searchNum"]; 
        
                else 
                        $searchNum="0"; 
                
               // recherche  des utilisateur 
               $users=$repo->findByExampleFieldA($_POST["username"]); 
                   


            return $this->render('site/searchUser.html.twig',['searchNum'=>$searchNum,'users'=>$users,'username'=>$session->get('username')]);
           }
           else
           {
                $repo=$this->getDoctrine()->getRepository(User::class); 
                $users=$repo->findAllT(); 
    
                // usersNum
                        
                $usersNum=$repo->usersNum();
                        if($usersNum)
                            $usersNum=$usersNum[0]["usersNum"]; 
                
                        else 
                                $usersNum="0"; 

               //passer une message 

               if(isset( $_GET['message']))
               {
                   $message= $_GET['message'];  
               }
               else
               {
                 $message=null; 
               }                                
    
                return $this->render('site/users.html.twig',['users'=>$users,'usersNum'=>$usersNum,'username'=>$session->get('username'),'message'=>$message]);
           }

        }
        else
        {
            return $this->render('site/login.html.twig');  
        }     
     }


 /**
     * @Route("/admin/articles", name="articles")
     */
    public function articles()
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       
       if($session->get('connected')=='true')
       {
          if(isset($_POST["search"]))
          {   
               $repo=$this->getDoctrine()->getRepository(Article::class);
               //savoir combien de resulat qu'on a de cet recherche  
               $searchNum=$repo->searchNum($_POST["articlename"]);
               if($searchNum)
                   $searchNum=$searchNum[0]["searchNum"]; 
       
               else 
                       $searchNum="0"; 
               
              // recherche  des articles
              $articles=$repo->findByExampleFieldA($_POST["articlename"]); 

             
                  

           return $this->render('site/searchArticle.html.twig',['searchNum'=>$searchNum,'articles'=>$articles]);
          }
          else
          {
               $repo=$this->getDoctrine()->getRepository(Article::class); 
               $articles=$repo->findAllT(); 
   
               // articlesNum
                       
               $articlesNum=$repo->articlesNum();
                       if($articlesNum)
                           $articlesNum=$articlesNum[0]["articlesNum"]; 
               
                       else 
                               $articlesNum="0"; 
               //passer une message  

               if(isset( $_GET['message']))
               {
                   $message= $_GET['message'];  
               }
               else
               {
                 $message=null; 
               }                                  
   
               return $this->render('site/articles.html.twig',['articles'=>$articles,'articlesNum'=>$articlesNum,'message'=>$message]);
          }

       }
       else
       {
           return $this->render('site/login.html.twig');  
       }     
    }  
    
 /**
     * @Route("/admin/sections", name="sections")
     */
    public function sections()
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       
       if($session->get('connected')=='true')
       {
            if(isset($_POST["search"]))
            {   
                $repo=$this->getDoctrine()->getRepository(Section::class);
                //savoir combien de resulat qu'on a de cet recherche  
                $searchNum=$repo->searchNum($_POST["sectionname"]);
                if($searchNum)
                    $searchNum=$searchNum[0]["searchNum"]; 
        
                else 
                        $searchNum="0"; 
                
                // recherche  des articles
                $sections=$repo->findByExampleFieldA($_POST["sectionname"]); 

                
                    

            return $this->render('site/searchSection.html.twig',['searchNum'=>$searchNum,'sections'=>$sections]);
            }


            else
            {
               $repo=$this->getDoctrine()->getRepository(Section::class); 
               $sections=$repo->findAllT(); 
   
               // articlesNum
                       
               $categoriesNum=$repo->categoriesNum();
                       if($categoriesNum)
                           $categoriesNum=$categoriesNum[0]["categoriesNum"]; 
               
                       else 
                               $categoriesNum="0";

              //passer une message  

              if(isset( $_GET['message']))
              {
                  $message= $_GET['message'];  
              }
              else
              {
                $message=null; 
              } 
              //passer une class  

              if(isset( $_GET['class']))
              {
                  $class= $_GET['class'];  
              }
              else
              {
                $class=null; 
              }                                                 
   
               return $this->render('site/sections.html.twig',['sections'=>$sections,'categoriesNum'=>$categoriesNum,'message'=>$message,'class'=>$class]);
            }

       }
       else
       {
           return $this->render('site/login.html.twig');  
       }     
    }
    
 /**
     * @Route("/admin/profil", name="profil")
     */
    public function profil()
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       
       if($session->get('connected')=='true')
       {
           $repo=$this->getDoctrine()->getRepository(User::class); 
           $user=$repo->findOneByUserNameA($session->get('username'));
               return $this->render('site/profil.html.twig',['user'=>$user]);
           

       }
       else
       {
           return $this->render('site/login.html.twig');  
       }     
    } 
/**
     * @Route("/admin/showUser/{id}", name="showUser")
     */
    public function showUser($id)
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       
       if($session->get('connected')=='true')
       {
           
        $repo=$this->getDoctrine()->getRepository(User::class); 
        $user=$repo->find($id); 
               return $this->render('site/showUser.html.twig',['user'=>$user]);
           

       }
       else
       {
           return $this->render('site/login.html.twig');  
       }     
    }   
    
/**
     * @Route("/admin/showArticle/{id}", name="showArticle")
     */
    public function showArticle($id)
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       
       if($session->get('connected')=='true')
       {
           
        $repo=$this->getDoctrine()->getRepository(Article::class); 
        $article=$repo->find($id); 


        // commentsNum
        $repoComment=$this->getDoctrine()->getRepository(Comment::class);            
        $commentsNum=$repoComment->commentsNum();
            if($commentsNum)
                    $commentsNum=$commentsNum[0]["commentsNum"]; 
        
                    else 
                        $commentsNum="0";


            return $this->render('site/showArticle.html.twig',['article'=>$article,'commentsNum'=>$commentsNum]);           

       }
       else
       {
           return $this->render('site/login.html.twig');  
       }     
    }   
 // fonction pour supprimer une commentaire 
    /**
     * @Route("/admin/deleteCA/{id}/{idC}", name="deleteCA")
     */
    public function deleteCA($id,$idC,ObjectManager $manager)
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       
       if($session->get('connected')=='true')
       {
           
        $repo=$this->getDoctrine()->getRepository(Article::class); 
        $article=$repo->find($id); 


        // commentsNum
        $repoComment=$this->getDoctrine()->getRepository(Comment::class);            
        $commentsNum=$repoComment->commentsNum();
            if($commentsNum)
                    $commentsNum=$commentsNum[0]["commentsNum"]; 
        
                    else 
                        $commentsNum="0";

        // supprimer une commentaire 
        
        $repoC=$this->getDoctrine()->getRepository(Comment::class); 
        $deleteCA=$repoC->deleteCA($idC);
        $message= "Vous avez bien supprimé cette commentaire"; 

         


            return $this->render('site/showArticle.html.twig',['article'=>$article,'commentsNum'=>$commentsNum,'message'=>$message]);           

       }
       else
       {
           return $this->render('site/login.html.twig');  
       }     
    }          
// fonction pour supprimer un article
 /**
     * @Route("/admin/deleteArticleA/{id}", name="deleteArticleA")
     */
    public function deleteArticleA($id)
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       
       if($session->get('connected')=='true')
       {
           
        $repo=$this->getDoctrine()->getRepository(Article::class); 

       //supprimer toutes les commentaires qui attachent avec un article
       $repoCom=$this->getDoctrine()->getRepository(Comment::class); 
       $repoCom->deleteAllCommentsAA($id); 
        // supprimer un article
        $deleteArticleA=$repo->deleteArticleA($id);  
        $message="Vous avez bien supprimé cet article"; 


       
             

        return $this->redirectToRoute('articles',['message'=>$message]);
       }
       else
       {
           return $this->render('site/login.html.twig');  
       }     
    }   

// fonction pour supprimer un utilisateur
 /**
     * @Route("/admin/deleteUserA/{id}", name="deleteUserA")
     */
    public function deleteUserA($id)
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       
       if($session->get('connected')=='true')
       {
           
        $repo=$this->getDoctrine()->getRepository(User::class); 
        $repoA=$this->getDoctrine()->getRepository(Article::class); 
        $deleteAllArtilcesAU=$repoA->deleteAllArticlesAU($id); 
        $repoC=$this->getDoctrine()->getRepository(Comment::class); 
        $deleteAllArtilcesAU=$repoC->deleteAllCommentsAU($id);
        $repoTch=$this->getDoctrine()->getRepository(Tchat::class);
        $repoTch->deleteAllTchAU($id); 
        
        // supprimer un utilisateur
        $deleteUserA=$repo->deleteUserA($id);  
        $message="Vous avez bien supprimé cet utilisateur"; 
        
    


        return $this->redirectToRoute('users', ['message'=>$message] );        

               
       }
       else
       {
           return $this->render('site/login.html.twig');  
       }     
    }   


// fonction pour supprimer une section 
/**
     * @Route("/admin/deleteSectionA/{id}", name="deleteSectionA")
     */
    public function deleteSectionA($id)
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       
       if($session->get('connected')=='true')
       {
           
        $repo=$this->getDoctrine()->getRepository(Section::class); 
        $reposub=$this->getDoctrine()->getRepository(Subsection::class);
        $repoArt=$this->getDoctrine()->getRepository(Article::class);
        $repoCom=$this->getDoctrine()->getRepository(Comment::class);
        $repoTch=$this->getDoctrine()->getRepository(Tchat::class);
        $idsOfsubsection=$reposub->searchidsOfSubsection($id); 
        if(!empty($idsOfsubsection))
        {
            foreach($idsOfsubsection as $value)
            {
                // chercher des articles de chaque subsection et supprimer les commentaires
               if(!empty($repoArt->searchidsOfArticle($value['id'] )))
                foreach( $repoArt->searchidsOfArticle($value['id'] )as $value1)
               {
                     $repoCom->deleteAllCommentsAA($value1['id']); 
               }              

                $repoArt->deleteAllArticlesPS($value['id']); 
            }
        }

        $deleteAllSubAA=$reposub->deleteAllSubAA($id); 
        $repoTch->deleteAllTchAA($id); 
        // supprimer une section 
        $deleteSectionA=$repo->deleteSectionA($id);  
        $message="Vous avez bien supprimé cette Catégorie"; 
       
   
        

        return $this->redirectToRoute('sections',['message'=>$message]);

        
       }
       else
       {
           return $this->render('site/login.html.twig');  
       }     
    }   


    // supprimer une sous-catégorie
/**
     * @Route("/admin/deleteSubsectionA/{id}", name="deleteSubsectionA")
     */
    public function deleteSubsectionA($id)
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       
       if($session->get('connected')=='true')
       {
           
        
        $reposub=$this->getDoctrine()->getRepository(Subsection::class);
        $repoArt=$this->getDoctrine()->getRepository(Article::class);
        $repoCom=$this->getDoctrine()->getRepository(Comment::class);
       
        
       
         
                // chercher des articles de chaque subsection et supprimer les commentaires
               if(!empty($repoArt->searchidsOfArticle($id )))
                foreach( $repoArt->searchidsOfArticle($id )as $value1)
               {
                     $repoCom->deleteAllCommentsAA($value1['id']); 
               }              

                $repoArt->deleteAllArticlesPS($id); 
               
       
        
        // supprimer une subsection 
        $reposub->deleteSubPId($id); 
        $message="Vous avez bien supprimé cette Sous-Catégorie"; 
       
   
        

        return $this->redirectToRoute('sections',['message'=>$message]);

        
       }
       else
       {
           return $this->render('site/login.html.twig');  
       }     
    }   



// Ajouter une catégorie 

/**
     * @Route("/admin/addSectionA", name="addSectionA")
     */
    public function addSectionA(ObjectManager $manager)
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       $repo=$this->getDoctrine()->getRepository(Section::class);
    if($session->get('connected')=='true')
       {
           $message=null; 
        if(isset($_POST['validation']))
        {
           $existe=$repo->findByName($_POST['name']); 
           if($existe)
           {
                $message="Choisie un autre nom de catégorie car ce nom existe déjà";
                return $this->render('site/addSectionA.html.twig',['message'=>$message]);
           }
           else 
           {
               $section=new Section(); 
               $section->setName($_POST['name'])
                        ->setDescription($_POST['description']); 
               $manager->persist($section); 
               $manager->flush();          
               return $this->redirectToRoute('sections',['message'=>'Vous avez bien ajouté une catégoie','class'=>"alert-success"]);           
           }
           
        }
       else 
       { 
         return $this->render('site/addSectionA.html.twig',['message'=>$message]);
       }
        
       }
    else
       {
           return $this->render('site/login.html.twig');  
       }     
    }   

 /**
     * @Route("/admin/developpeur", name="editSubsectionA")
     */
    public function developpeur()
    {  
        return $this->render('site/developpeur.html.twig'); 
     }
         
// Ajouter une sous-catégorie

/**
     * @Route("/admin/addSubsectionA", name="addSubsectionA")
     */
    public function addSubsectionA(ObjectManager $manager)
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       $repoSec=$this->getDoctrine()->getRepository(Section::class);
       $repoSub=$this->getDoctrine()->getRepository(Subsection::class);
       $categories=$repoSec->findAllT(); 

    if($session->get('connected')=='true')
       {
           $message=null; 
        if(isset($_POST['validation']))
        {
            

              $subsection=new Subsection(); 
              
               $categorieSelect=$repoSec->find($_POST['section']); 
              
               dump($categorieSelect); 
               dump($_POST['section']); 
               $subsection->setName($_POST['name'])                          
                          ->setDescription($_POST['description'])
                          ->setSection( $categorieSelect);   
                        
           
            
               $manager->persist($subsection); 
               $manager->flush();          
               return $this->redirectToRoute('sections',['message'=>'Vous avez bien ajouté une sous-catégoie','class'=>"alert-success"]);           
      
           
        }
       else 
       { 
         return $this->render('site/addSubsectionA.html.twig',['message'=>$message,'categories'=>$categories]);
       }
        
       }
    else
       {
           return $this->render('site/login.html.twig');  
       }     
    }   

    // ajouter un utilisateur
/**
     * @Route("/admin/addUserA", name="addUserA")
     */
    public function addUserA(ObjectManager $manager)
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       $repo=$this->getDoctrine()->getRepository(User::class);
    if($session->get('connected')=='true')
       {
           $message=null; 
        if(isset($_POST['validation']))
        {
           $existe=$repo->findByUsername($_POST['username']); 
           if($existe)
           {
                $message="Choisie un autre pseudo car ce pseudo existe déjà";
                return $this->render('site/addUserA.html.twig',['message'=>$message]);
           }
           else 
           {
               if($_POST['passwordC']!=$_POST['password'])
               {
                    $message="La confirmation de mot de passe n'est pas correcte.";
                    return $this->render('site/addUserA.html.twig',['message'=>$message]);
               }
               else 
               {
                    $user=new User(); 
                    $user->setUsername($_POST['username'])
                         ->setAdmin($_POST['admin'])
                         ->setFirstname($_POST['firstname'])
                         ->setLastname($_POST['lastname'])
                         ->setSex($_POST['sex'])
                         ->setEmail($_POST['email'])
                         ->setPhone($_POST['phone'])
                         ->setAddress($_POST['address'])
                         ->setZipcode($_POST['arrondissement']) 
                         ->setCandidat($_POST['candidat']) 
                         ->setBirthday($_POST['birthday']) 
                         ->setPassword(sha1($_POST['password']))                          
                         ->setCreatedAt(new \DateTime()) 
                         ->setAvatar('/images/defaultProfilA.png');   
                                            


                    $manager->persist($user); 
                    $manager->flush();          
                    return $this->redirectToRoute('users',['message'=>'Vous avez bien ajouté un catégorie']); 
               }
              
           }
           
        }
       else 
       { 
         return $this->render('site/addUserA.html.twig',['message'=>$message]);
       }
        
       }
    else
       {
           return $this->render('site/login.html.twig');  
       }     
    }   


// modifier une catégorie 

/**
     * @Route("/admin/editSectionA/{id}", name="editSectionA")
     */
    public function editSectionA($id,ObjectManager $manager)
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       $repo=$this->getDoctrine()->getRepository(Section::class);
       $section=$repo->findOneById($id); 
    if($session->get('connected')=='true')
       {
           $message=null; 
        if(isset($_POST['validation']))
        {
          if($_POST['name']!=$_POST['nameC'])
            {
                $existe=$repo->findByName($_POST['name']); 
                if($existe)
                {
                        $message="Choisie un autre nom de catégorie car ce nom existe déjà";
                        return $this->render('site/editSectionA.html.twig',['message'=>$message,'section'=>$section]);
                }
                else 
                {
                    
                    // modifier le nom de section
                    $section->setName($_POST['name']);
                             
                    $manager->persist($section); 
                    $manager->flush();          
                    return $this->redirectToRoute('sections',['message'=>'Vous avez bien renommé  la  catégoie','class'=>"alert-warning"]);           
                }
             } 
             else
             {
                 $message="Vous avez rien changé car vous avez écrit le mâme nom de catégorie"; 
                 return $this->render('site/editSectionA.html.twig',['message'=>$message,'section'=>$section]);
             }
        }
       else 
       { 
         return $this->render('site/editSectionA.html.twig',['message'=>$message,'section'=>$section]);
       }
        
       }
    else
       {
           return $this->render('site/login.html.twig');  
       }     
    }   

// modifier une sous-catégorie 

/**
     * @Route("/admin/editSubsectionA/{id}", name="editSubsectionA")
     */
    public function editSubsectionA($id,ObjectManager $manager)
    {
       $session = new Session(new NativeSessionStorage(), new AttributeBag()); 
       $repo=$this->getDoctrine()->getRepository(Subsection::class);
       $subsection=$repo->findOneById($id); 
    if($session->get('connected')=='true')
       {
           $message=null; 
        if(isset($_POST['validation']))
        {
          if($_POST['name']!=$_POST['nameC'])
            {
  
                    
                    // modifier le nom de subsection
                    $subsection->setName($_POST['name']);
                             
                    $manager->persist($subsection); 
                    $manager->flush();          
                    return $this->redirectToRoute('sections',['message'=>'Vous avez bien renommé cette sous-catégoie','class'=>"alert-warning"]);           
               
             } 
          else
             {
                 $message="Vous avez rien changé car vous avez écrit le mâme nom de sous-catégorie"; 
                 return $this->render('site/editSubsectionA.html.twig',['message'=>$message,'subsection'=>$subsection]);
             }
        }
       else 
       { 
         return $this->render('site/editSubsectionA.html.twig',['message'=>$message,'subsection'=>$subsection]);
       }
        
       }
    else
       {
           return $this->render('site/login.html.twig');  
       }     
    }       


}

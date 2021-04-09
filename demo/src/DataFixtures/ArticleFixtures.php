<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ArticleFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    
    public function load(ObjectManager $manager) 
    {

        $faker = \Faker\Factory::create('fr_FR');

        $admin = new User();
        $admin->setUsername("admin");
        $admin->setEmail("admin@admin.fr");
        $hash = $this->encoder->encodePassword($admin, "admin");
        $admin->setPassword($hash);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        
       

        //Crée 3 catégories fakées
        for($i = 1; $i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);
            
            // Créer entre 4 et 6 articles
            for ($j = 1; $j <= mt_rand(4,6); $j++) {
                $article = new Article();

                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';



                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage("https://picsum.photos/350/150")
                    ->setCreateAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category);

             $manager->persist($article); 
             
             // On donne des commentaires à l'article
             for($k = 1; $k <= mt_rand(4, 10); $k++) {
                 $comment = new Comment();

                 $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';
  
                 
                 $days = (new \DateTime())->diff($article->getCreateAt())->days;
                 

                 $comment->setAuthor($faker->name)
                         ->setContent($content)
                         ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                         ->setArticle($article);

                         $manager->persist($comment);

              }
            }
        }
          
        $manager->flush();
    }
}

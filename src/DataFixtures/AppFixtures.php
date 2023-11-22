<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTime;

use App\Entity\User;
use App\Entity\Photo;
use App\Entity\LikesPhoto;
use App\Entity\Commentaire;
use App\Entity\LikesCommentaire;

class AppFixtures extends Fixture
{

    private $passwordHasher;

    public  function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        //Ajout des utilisateurs
        $user1 = new User();
        $user1->setUserName('admin');
        $user1->setRoles(["ROLE_ADMIN"]);
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'admin'));
        $user1->setAvatar("avatar");
        $user1->setBan(false);
        $manager->persist($user1);
        $manager->flush();

        $user2 = new User();
        $user2->setUsername('standard');
        $user2->setRoles(["ROLE_USER"]);
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'standard'));
        $user2->setAvatar("avatar");
        $user2->setDescription("Juliette, 25 ans, Marseille");
        $user2->setBan(true);
        $manager->persist($user2);
        $manager->flush();

        $user3 = new User();
        $user3->setUsername('lambda');
        $user3->setRoles(["ROLE_USER"]);
        $user3->setPassword($this->passwordHasher->hashPassword($user3, 'lambda'));
        $user3->setAvatar("avatar");
        $user3->setDescription("Moi c'est Anthonio et j'adore les fruits et légumes");
        $user3->setBan(false);
        $manager->persist($user3);
        $manager->flush();

        $user4 = new User();
        $user4->setUsername('modo');
        $user4->setRoles(["ROLE_ADMIN"]);
        $user4->setPassword($this->passwordHasher->hashPassword($user4, 'modo'));
        $user4->setAvatar("avatar");
        $user4->setDescription("Représente le 93 avec les meilleures fruits du marché");
        $user4->setBan(false);
        $manager->persist($user4);
        $manager->flush();

        //Tout les utilisateurs
        $user1 = $manager->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $user2 = $manager->getRepository(User::class)->findOneBy(['username' => 'standard']);
        $user3 = $manager->getRepository(User::class)->findOneBy(['username' => 'lambda']);
        $user4 = $manager->getRepository(User::class)->findOneBy(['username' => 'modo']);

        //Ajout des photos
        //Photo 1
        $datePhoto1 = DateTime::createFromFormat('Y-m-d H:i:s', '2023-11-15 17:00:00');
        
        $photo1 = new Photo();
        $photo1->setImage("default");
        $photo1->setDatePoste($datePhoto1);
        $photo1->setLikesCount(40);
        $photo1->setDislikesCount(10);
        $photo1->setIsLocked(false);
        $photo1->setUser($user1);
        $manager->persist($photo1);
        $manager->flush();

        //Photo 2
        $datePhoto3 = DateTime::createFromFormat('Y-m-d H:i:s', '2023-04-01 08:30:00');

        $photo2 = new Photo();
        $photo2->setImage("default");
        $photo2->setDescription("Qu'il y a t'il de plus beau que cette poire ?");
        $photo2->setDatePoste($datePhoto3);
        $photo2->setLikesCount(4000);
        $photo2->setDislikesCount(1000);
        $photo2->setIsLocked(false);
        $photo2->setUser($user1);
        $manager->persist($photo2);
        $manager->flush();

        //Photo 3
        $datePhoto2 = DateTime::createFromFormat('Y-m-d H:i:s', '2023-11-15 08:30:00');

        $photo3 = new Photo();
        $photo3->setImage("default");
        $photo3->setDescription("Voici une belle pomme que j'ai acheté ce matin au marché");
        $photo3->setDatePoste($datePhoto2);
        $photo3->setLikesCount(1200);
        $photo3->setDislikesCount(400);
        $photo3->setIsLocked(true);
        $photo3->setUser($user2);
        $manager->persist($photo3);
        $manager->flush();

        //Photo 4
        $datePhoto4 = DateTime::createFromFormat('Y-m-d H:i:s', '2023-08-13 08:30:00');

        $photo4 = new Photo();
        $photo4->setImage("default");
        $photo4->setDescription("Très joli plat que j'ai cuisiné ce matin");
        $photo4->setDatePoste($datePhoto4);
        $photo4->setLikesCount(100);
        $photo4->setDislikesCount(350);
        $photo4->setIsLocked(false);
        $photo4->setUser($user3);
        $manager->persist($photo4);
        $manager->flush();

        //Ajout des commentaires
        //Commentaire 1
        $dateCom1 = DateTime::createFromFormat('Y-m-d H:i:s', '2023-11-16 10:00:00');

        $commentaire1 = new Commentaire();
        $commentaire1->setDescription("Quelle jolie photo");
        $commentaire1->setDateCommentaire($dateCom1);
        $commentaire1->setLikesCount(122);
        $commentaire1->setDislikesCount(35);
        $commentaire1->setUser($user3);
        $commentaire1->setPhoto($photo1);
        $manager->persist($commentaire1);
        $manager->flush();

        //Commentaire 2
        $dateCom2 = DateTime::createFromFormat('Y-m-d H:i:s', '2023-11-18 11:20:00');

        $commentaire2 = new Commentaire();
        $commentaire2->setDescription("Oui, très beau");
        $commentaire2->setDateCommentaire($dateCom2);
        $commentaire2->setLikesCount(10);
        $commentaire2->setDislikesCount(5);
        $commentaire2->setUser($user4);
        $commentaire2->setPhoto($photo1);
        $commentaire2->setCommentaire($commentaire1);
        $manager->persist($commentaire2);
        $manager->flush();

        //Commentaire 3
        $dateCom3 = DateTime::createFromFormat('Y-m-d H:i:s', '2023-10-04 11:20:00');

        $commentaire3 = new Commentaire();
        $commentaire3->setDescription("Quelles sont belles !");
        $commentaire3->setDateCommentaire($dateCom3);
        $commentaire3->setLikesCount(155);
        $commentaire3->setDislikesCount(100);
        $commentaire3->setUser($user1);
        $commentaire3->setPhoto($photo3);
        $manager->persist($commentaire3);
        $manager->flush();

        //Commentaire 4
        $dateCom4 = DateTime::createFromFormat('Y-m-d H:i:s', '2023-09-12 12:40:00');

        $commentaire4 = new Commentaire();
        $commentaire4->setDescription("Miam !");
        $commentaire4->setDateCommentaire($dateCom4);
        $commentaire4->setLikesCount(155);
        $commentaire4->setDislikesCount(100);
        $commentaire4->setUser($user2);
        $commentaire4->setPhoto($photo4);
        $manager->persist($commentaire4);
        $manager->flush();

        //Ajout de Likes_Commentaire 1
        $likeCom1 = new LikesCommentaire();
        $likeCom1->setLikeType(true);
        $likeCom1->setUser($user2);
        $likeCom1->setCommentaire($commentaire1);
        $manager->persist($likeCom1);
        $manager->flush();

        //Ajout de Likes_Commentaire 2
        $likeCom2 = new LikesCommentaire();
        $likeCom2->setLikeType(true);
        $likeCom2->setUser($user3);
        $likeCom2->setCommentaire($commentaire1);
        $manager->persist($likeCom2);
        $manager->flush();

        //Ajout de Likes_Commentaire 3
        $likeCom3 = new LikesCommentaire();
        $likeCom3->setLikeType(false);
        $likeCom3->setUser($user4);
        $likeCom3->setCommentaire($commentaire1);
        $manager->persist($likeCom3);
        $manager->flush();

        //Ajout de Likes_Commentaire 4
        $likeCom4 = new LikesCommentaire();
        $likeCom4->setLikeType(false);
        $likeCom4->setUser($user1);
        $likeCom4->setCommentaire($commentaire2);
        $manager->persist($likeCom4);
        $manager->flush();

        //Ajout de Likes_Commentaire 5
        $likeCom5 = new LikesCommentaire();
        $likeCom5->setLikeType(true);
        $likeCom5->setUser($user4);
        $likeCom5->setCommentaire($commentaire2);
        $manager->persist($likeCom5);
        $manager->flush();

         //Ajout de Likes_Commentaire 6
         $likeCom6 = new LikesCommentaire();
         $likeCom6->setLikeType(true);
         $likeCom6->setUser($user3);
         $likeCom6->setCommentaire($commentaire3);
         $manager->persist($likeCom6);
         $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTime;

use App\Entity\User;
use App\Entity\Photo;
use App\Entity\Commentaire;

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


        //Ajout des photos

        //Photo 1
        $datePhoto1 = DateTime::createFromFormat('Y-m-d H:i:s', '2023-11-15 17:00:00');
        $user_id1 = $manager->getRepository(User::class)->findOneBy(['username' => 'admin']);

        $photo1 = new Photo();
        $photo1->setImage("default");
        $photo1->setDatePoste($datePhoto1);
        $photo1->setLikesCount(40);
        $photo1->setDislikesCount(10);
        $photo1->setIsLocked(false);
        $photo1->setUser($user_id1);
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
        $photo2->setUser($user_id1);
        $manager->persist($photo2);
        $manager->flush();

        //Photo 3
        $datePhoto2 = DateTime::createFromFormat('Y-m-d H:i:s', '2023-11-15 08:30:00');
        $user_id2 = $manager->getRepository(User::class)->findOneBy(['username' => 'standard']);

        $photo3 = new Photo();
        $photo3->setImage("default");
        $photo3->setDescription("Voici une belle pomme que j'ai acheté ce matin au marché");
        $photo3->setDatePoste($datePhoto2);
        $photo3->setLikesCount(1200);
        $photo3->setDislikesCount(400);
        $photo3->setIsLocked(true);
        $photo3->setUser($user_id2);
        $manager->persist($photo3);
        $manager->flush();

        //Photo 4
        $datePhoto4 = DateTime::createFromFormat('Y-m-d H:i:s', '2023-08-13 08:30:00');
        $user_id3 = $manager->getRepository(User::class)->findOneBy(['username' => 'lambda']);

        $photo4 = new Photo();
        $photo4->setImage("default");
        $photo4->setDescription("Très joli plat que j'ai cuisiné ce matin");
        $photo4->setDatePoste($datePhoto4);
        $photo4->setLikesCount(100);
        $photo4->setDislikesCount(350);
        $photo4->setIsLocked(false);
        $photo4->setUser($user_id3);
        $manager->persist($photo4);
        $manager->flush();

        //Ajout des commentaires

        //Commentaire 1
        $dateCom1 = DateTime::createFromFormat('Y-m-d H:i:s', '2023-11-16 10:00:00');
        $userCom1 = $manager->getRepository(User::class)->findOneBy(['username' => 'lambda']);
        $photoCom1 = $manager->getRepository(Photo::class)->findOneBy(['user_id' => 1]);

        $commentaire1 = new Commentaire();
        $commentaire1->setDescription("Quelle jolie photo");
        $commentaire1->setDateCommentaire($dateCom1);
        $commentaire1->setLikesCount(122);
        $commentaire1->setDislikesCount(35);
        $commentaire1->setUser($userCom1);
        $commentaire1->setPhoto($photoCom1);
        $manager->persist($commentaire1);
        $manager->flush();

    }
}

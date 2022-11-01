<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create();
        for ($i = 0; $i < 4; $i++) {
            $user = new User();
            $password = $this->hasher->hashPassword($user, 'admin');

            $user->setEmail('test' . $i . '@gmail.com');
            $user->setPassword($password);
            $manager->persist($user);
            for ($j = 0; $j < 5; $j++) {
                $article = new Article();
                $article->setTitle($faker->name);
                $article->setContent($faker->text(1500));
                $article->setCreatedAt(new DateTimeImmutable());
                $article->setAuthor($user);
                $article->setImage('https://via.placeholder.com/1440x550');
                $manager->persist($article);
                for ($k = 0; $k < 3; $k++) {
                    $comment = new Comment();
                    $comment->setName($faker->name);
                    $comment->setUrl($faker->url);
                    $comment->setEmail($faker->email);
                    $comment->setContent($faker->text(50));
                    $comment->setCreatedAt(new DateTimeImmutable());
                    $comment->setArticle($article);
                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}

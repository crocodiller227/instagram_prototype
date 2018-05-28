<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadInstagramData implements FixtureInterface, ORMFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $users = [];
        $posts = [];

        for ($i = 1; $i < 6; $i++) {
            $user = new User();
            $user
                ->setUsername("user_{$i}")
                ->setPlainPassword('root')
                ->setEmail("user_{$i}@gmail.com")
                ->setEnabled(true)
                ->setRoles(['ROLE_USER'])
                ->setAvatar("user_{$i}.png");
            for ($j = 1; $j < 6; $j++) {
                $post = new Post();
                $post
                    ->setUser($user)
                    ->setPublishDate(new \DateTime())
                    ->setImage("user_{$i}_{$j}.jpeg")
                    ->setTitle("my post #{$j}");

                $posts[] = $post;


            }
            $users[] = $user;
        }

        foreach ($users as $key => $user) {
            $potential_user = $users;

            foreach ($posts as $post) {

                if (rand(0, 1)) {
                    $first_comment = new Comment();
                    $first_comment
                        ->setPublishDate(new \DateTime())
                        ->setUser($user)
                        ->setComment('Hey, how are you?')
                        ->setPost($post);
                    $post->addComments($first_comment);
                    $manager->persist($first_comment);
                }

                if (rand(0, 1)) {
                    $second_comment = new Comment();
                    $second_comment
                        ->setPublishDate(new \DateTime())
                        ->setUser($user)
                        ->setComment('Hello!')
                        ->setPost($post);
                    $post->addComments($second_comment);
                    $manager->persist($second_comment);
                }

                if (rand(0, 1)) {
                    $post->addLiker($user);
                }

                $manager->persist($post);
            }

            unset($potential_user[$key]);

            $first_following = $potential_user[array_rand($potential_user)];
            $user->follow($first_following);
            unset($potential_user[array_search($first_following, $potential_user)]);
            $second_following = $potential_user[array_rand($potential_user)];
            $user->follow($second_following);
            unset($potential_user[array_search($second_following, $potential_user)]);
            $third_following = $potential_user[array_rand($potential_user)];
            $user->follow($third_following);
            $manager->persist($user);

        }
        $manager->flush();

    }
}
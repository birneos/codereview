<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var Factory
     */
    private $faker;


    private const USERS = [
        [
            'username' => 'admin',
            'email' => 'admin@blog.com',
            'name' => 'Marco Linke',
            'password' => 'secret123#'
        ],
        [
            'username' => 'john_doe',
            'email' => 'john_doe@blog.com',
            'name' => 'John Doe',
            'password' => 'secret123#'
        ],
        [
            'username' => 'robbie_williams',
            'email' => 'robbie_williams@blog.com',
            'name' => 'Robbie Williams',
            'password' => 'secret123#'
        ],
        [
            'username' => 'rocky_balboa',
            'email' => 'rocky_balboa@blog.com',
            'name' => 'Rocky Balboa',
            'password' => 'secret123#'
        ],

    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create(); //first step

    }

    /**
     * Load data fixtures with passed  EntityManager
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
       $this->loadUsers($manager);
        $this->loadBlogPost($manager);
        $this->loadComments($manager);


    }

    public function loadBlogPost(ObjectManager $manager)
    {


        for($i=0; $i<100; $i++)
        {

            $blogpost = new BlogPost();
            $blogpost->setTitle($this->faker->realText(30));
            $blogpost->setPublished($this->faker->dateTimeThisYear);
            $blogpost->setSlug($this->faker->slug);
            $blogpost->setContent($this->faker->realText());

            $authorReference = $this->getRandomUserReference();
            $blogpost->setAuthor($authorReference);

            $this->setReference("blog_post_$i", $blogpost);
            $manager->persist($blogpost);
        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {

        for($i=0; $i<100; $i++){
            for($j=0;$j < rand(1,10); $j++){

                $comment = new Comment();
                $comment->setContent($this->faker->realText());

                $authorReference = $this->getRandomUserReference();

                $comment->setAuthor($authorReference);
                $comment->setPublished($this->faker->dateTime);
                $comment->setBlogPost($this->getReference("blog_post_$i"));
                $manager->persist($comment);
            }
        }
        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {

        foreach(self::USERS as $userFixture){

            $user = new User();
            $user->setUsername($userFixture['username']);
            $user->setName($userFixture['name']);
            $user->setEmail($userFixture['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user,$userFixture['password']));

            $this->addReference('user_' . $userFixture['username'], $user);
            $manager->persist($user);

        }

        $manager->flush();


    }

    /**
     * @return User|null
     */
    public function getRandomUserReference():User
    {

        return $this->getReference( 'user_' . self::USERS[rand(0, 2)]['username']);

    }
}

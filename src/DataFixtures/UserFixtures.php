<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");

        //Créer toujours un admin
        $user = new User();
        $user->setUsername('admin');
        $user->setFirstName('admin');
        $user->setLastName('admin');
        $user->setPhone($faker->phoneNumber);
        $user->setMail($faker->email);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setAdmin(1);
        $user->setPassword($this->encoder->encodePassword($user, '123456'));

        //Créer des utilisateurs random
         for($i = 0 ; $i < 10; $i++){
         $user = new User();
             $user->setUsername($faker->userName);
             $user->setFirstName($faker->firstName);
             $user->setLastName($faker->lastName);
             $user->setPhone($faker->phoneNumber);
             $user->setMail($faker->email);
             $user->setRoles(['ROLE_USER']);
             $user->setAdmin(0);
             $user->setPassword($this->encoder->encodePassword($user, '123456'));
         $manager->persist($user);
         }
        $manager->flush();
    }
}

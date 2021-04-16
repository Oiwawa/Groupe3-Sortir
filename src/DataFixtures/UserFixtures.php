<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");

        //Création de 4 campus
        for ($i = 0; $i < 5; $i++) {
            $campus = new Campus();
            $campus->setName($faker->city);
            $manager->persist($campus);

        }
        $manager->flush();

        //Récupération des campus
        $campuss = $manager->getRepository(Campus::class)->findAll();

        //Création d'au moins une entité admin
        $user = new User();
        $user->setUsername('Gandalf');
        $user->setFirstName('Gandalf');
        $user->setLastName('Le Gris');
        $user->setPhone($faker->phoneNumber);
        $user->setMail("gandalf@laComte.com");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setAdmin(1);
        $user->setPassword($this->encoder->encodePassword($user, 'admin'));
        $user->setCampus($faker->randomElement($campuss));

        $manager->persist($user);

        //Créer des utilisateurs random
        $manager->flush();
    }
}

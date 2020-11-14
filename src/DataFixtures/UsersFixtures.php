<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UsersFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($nbUsers = 1; $nbUsers <= 30; $nbUsers++){
            $user = new Users();
            $user->setEmail($faker->email);
            if($nbUsers === 1)
                $user->setRoles(['ROLE_ADMIN']);
            else
                $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->encoder->encodePassword($user, 'azerty'));
            $user->setName($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setIsVerified($faker->numberBetween(0, 1));
            $manager->persist($user);

            // Enregistre l'utilisateur dans une référence
            $this->addReference('user_'. $nbUsers, $user);
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Annonces;
use App\Entity\Images;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AnnoncesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($nbAnnonces = 1; $nbAnnonces <= 100; $nbAnnonces++){
            $user = $this->getReference('user_'. $faker->numberBetween(1, 30));
            $categorie = $this->getReference('categorie_'. $faker->numberBetween(1, 4));

            $annonce = new Annonces();
            $annonce->setUsers($user);
            $annonce->setCategories($categorie);
            $annonce->setTitle($faker->realText(25));
            $annonce->setContent($faker->realText(400));
            $annonce->setActive($faker->numberBetween(0, 1));

            // On uploade et on génère les images
            for($image = 1; $image <= 3; $image++){
                $img = $faker->image('public/uploads/images/annonces');
                $nomImg = basename($img);
                $imageAnnonce = new Images();
                $imageAnnonce->setName($nomImg);
                $annonce->addImage($imageAnnonce);
            }
            $manager->persist($annonce);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoriesFixtures::class,
            UsersFixtures::class
        ];
    }
}

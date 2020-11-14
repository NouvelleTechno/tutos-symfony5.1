<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = [
            1 => [
                'name' => 'Véhicules',
                'color' => '#ff80c0'
            ],
            2 => [
                'name' => 'Mobilier',
                'color' => '#80c0ff'
            ],
            3 => [
                'name' => 'Outils',
                'color' => '#c0ff80'
            ],
            4 => [
                'name' => 'Immobilier',
                'color' => '#00ff78'
            ],
        ];

        foreach($categories as $key => $value){
            $categorie = new Categories();
            $categorie->setName($value['name']);
            $categorie->setColor($value['color']);
            $manager->persist($categorie);

            // Enregistre la catégorie dans une référence
            $this->addReference('categorie_' . $key, $categorie);
        }

        $manager->flush();
    }
}

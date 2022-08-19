<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 100;  $i++) {
            $property = new Property();
            $property
                ->setTitle('Mon super bien ' . $i);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

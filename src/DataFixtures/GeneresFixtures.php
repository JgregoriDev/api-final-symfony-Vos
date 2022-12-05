<?php

namespace App\DataFixtures;

use App\Entity\Genere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
class GeneresFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i=0; $i < 8; $i++) { 
            $genere=new Genere();
            $genere->setGenere($this->faker->word());
            $manager->persist($genere);

        }
        $manager->flush();
    }
}

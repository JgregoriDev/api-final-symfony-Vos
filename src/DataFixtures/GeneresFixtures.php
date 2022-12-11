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
        $genere=new Genere();
        $genere->setGenere("vaquers");
        $manager->persist($genere);
        $genere=new Genere();
        $genere->setGenere("acció");
        $manager->persist($genere);
        $genere=new Genere();
        $genere->setGenere("aventura");
        $manager->persist($genere);
        $genere=new Genere();
        $genere->setGenere("estratègia");
        $manager->persist($genere);
        $genere=new Genere();
        $genere->setGenere("rol");
        $manager->persist($genere);
        for ($i=0; $i < 4; $i++) { 
            $genere=new Genere();
            $genere->setGenere($this->faker->word());
            $manager->persist($genere);

        }
        $manager->flush();
    }
}

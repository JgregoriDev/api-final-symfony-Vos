<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Marca;

class MarcaFixtures extends Fixture
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
        $marca=new Marca();
        $marca->setMarca("Nintendo");
        $manager->persist($marca);
        $marca=new Marca();
        $marca->setMarca("Windows");
        $manager->persist($marca);
        $marca=new Marca();
        $marca->setMarca("Sony");
        $manager->persist($marca);
        $marca=new Marca();
        $marca->setMarca("Steam");
        $manager->persist($marca);

        // for ($i=0; $i < 5; $i++) { 
        //     $marca=new Marca();
        //     $marca->setMarca($this->faker->word());
        //     $manager->persist($marca);

        // }
        $manager->flush();
    }
}

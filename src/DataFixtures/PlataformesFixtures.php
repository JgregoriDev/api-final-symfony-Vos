<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Plataforma;
use App\Entity\Marca;
class PlataformesFixtures extends Fixture
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
        $marques=$manager->getRepository(Marca::class)->findAll();
        for ($i=0; $i < 10; $i++) { 
            $plataforma=new Plataforma();
            $plataforma->setPlataforma($this->faker->word())
            ->setMarca($marques[$this->faker->numberBetween(0,3)]);
            $manager->persist($plataforma);

        }
        $manager->flush();
    }
}

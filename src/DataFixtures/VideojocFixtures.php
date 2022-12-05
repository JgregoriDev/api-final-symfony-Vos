<?php

namespace App\DataFixtures;

use App\Entity\Genere;
use App\Entity\Plataforma;
use App\Entity\Videojoc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
class VideojocFixtures extends Fixture
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
        $faker=$this->faker;
        $plataformes = $manager->getRepository(Plataforma::class)->findAll();
        $generes = $manager->getRepository(Genere::class)->findAll();
        for ($i=0; $i < 20; $i++) { 
            $videojoc=new Videojoc();
            $videojoc->setTitul($faker->word())
            ->setDescripcio($faker->text(254))
            ->setFechaEstreno($faker->dateTime())
            ->setCantitat($faker->numberBetween(0,100))
            ->setPortada($faker->imageUrl(640, 480, 'animals', true))
            ->setPreu($faker->numberBetween(0,300))
            ->addGenere($generes[$faker->numberBetween(0,6)])
            ->addVideojocPlataforma($plataformes[$this->faker->numberBetween(0,5)]);
            $manager->persist($videojoc);
        }
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Usuari;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class UsuariFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private Generator $faker;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $usuari=new Usuari();
        $usuari->setEmail("admin")
        ->setRoles(["ROLE_USER","ROLE_ADMIN"])
        ->setBan(false)
        ->setPassword( $this->hasher->hashPassword($usuari, "admin"));
        
        $manager->persist($usuari);
        
        $usuari=new Usuari();
        $usuari->setEmail("usuari")
        ->setRoles(["ROLE_USER"])
        ->setBan(false)
        ->setPassword( $this->hasher->hashPassword($usuari, "usuari"));
        
        $manager->persist($usuari);
        
        $usuari=new Usuari();
        $usuari->setEmail("user")
        ->setRoles(["ROLE_USER"])
        ->setBan(false)
        ->setPassword( $this->hasher->hashPassword($usuari, "user"));
        
        $manager->persist($usuari);
        $manager->flush(



        );
    }
}

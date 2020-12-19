<?php

namespace App\DataFixtures;

use App\Entity\Matrice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MatriceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $matrice = new Matrice();
        $matrice->setName('5x5');
        $matrice->setMultiple(5);
        $manager->persist($matrice);
        $this->addReference('5x5', $matrice);
        $manager->flush();
    }
}

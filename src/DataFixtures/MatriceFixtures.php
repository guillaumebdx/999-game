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
        $matrice->setName('9x9');
        $manager->persist($matrice);
        $this->addReference('9x9', $matrice);
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Block;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BlockFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i<=9; $i++) {
            $block = new Block();
            $block->setX($i);
            for ($j=1; $j<=9; $j++) {
                $block->setY($j);
            }
            $manager->persist($block);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          MatriceFixtures::class,
        ];
    }


}

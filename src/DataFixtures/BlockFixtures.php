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
        for ($i=1; $i<=5; $i++) {
            for ($j=1; $j<=5; $j++) {
                $block = new Block();
                $block->setX($i);
                $block->setY($j);
                $block->setNumber(rand(1, Block::MAX_NUMBER -1));
                $block->setMatrice($this->getReference('5x5'));
                $manager->persist($block);
            }
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

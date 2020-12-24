<?php

namespace App\DataFixtures;

use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LevelFixtures extends Fixture
{
    const LEVELS = [
        [
            'maxNumber' => 3,
            'multiple'  => 3,
            'target'    => 150,
        ],
        [
            'maxNumber' => 4,
            'multiple'  => 3,
            'target'    => 150,
        ],
        [
            'maxNumber' => 4,
            'multiple'  => 4,
            'target'    => 200,
        ],
        [
            'maxNumber' => 4,
            'multiple'  => 5,
            'target'    => 300,
        ],
        [
            'maxNumber' => 4,
            'multiple'  => 5,
            'target'    => 500,
        ],
        [
            'maxNumber' => 4,
            'multiple'  => 5,
            'target'    => 800,
        ],
        [
            'maxNumber' => 5,
            'multiple'  => 5,
            'target'    => 400,
        ],
        [
            'maxNumber' => 5,
            'multiple'  => 5,
            'target'    => 600,
        ],
        [
            'maxNumber' => 6,
            'multiple'  => 5,
            'target'    => 500,
        ],
        [
            'maxNumber' => 6,
            'multiple'  => 5,
            'target'    => 700,
        ],
        [
            'maxNumber' => 6,
            'multiple'  => 5,
            'target'    => 800,
        ],
        [
            'maxNumber' => 3,
            'multiple'  => 6,
            'target'    => 1000,
        ],
        [
            'maxNumber' => 7,
            'multiple'  => 6,
            'target'    => 500,
        ],
        [
            'maxNumber' => 7,
            'multiple'  => 5,
            'target'    => 600,
        ],
        [
            'maxNumber' => 7,
            'multiple'  => 5,
            'target'    => 800,
        ],
        [
            'maxNumber' => 8,
            'multiple'  => 5,
            'target'    => 400,
        ],
        [
            'maxNumber' => 8,
            'multiple'  => 6,
            'target'    => 500,
        ],
        [
            'maxNumber' => 8,
            'multiple'  => 6,
            'target'    => 700,
        ],
        [
            'maxNumber' => 6,
            'multiple'  => 5,
            'target'    => 1000,
        ],

    ];
    public function load(ObjectManager $manager)
    {
        $inc = 0;
        foreach (self::LEVELS as $levelData) {
            $level = new Level();
            $level->setLevel($inc);
            $level->setMaxNumber($levelData['maxNumber']);
            $level->setMultiple($levelData['multiple']);
            $level->setTarget($levelData['target']);
            $manager->persist($level);
            $inc++;
        }


        $manager->flush();
    }
}

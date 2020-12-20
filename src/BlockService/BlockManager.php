<?php


namespace App\BlockService;


use App\Entity\Block;
use App\Repository\BlockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;

class BlockManager
{
    private $entityManager;

    private $blockRepository;

    public function __construct(EntityManagerInterface $entityManager, BlockRepository $blockRepository)
    {
        $this->entityManager = $entityManager;
        $this->blockRepository = $blockRepository;
    }


    public function isTop($block)
    {
        $isTop = false;

        if ($block->getX() === 1) {
            $isTop = true;
        }
        return $isTop;
    }

}

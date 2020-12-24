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

    private $scoreManager;

    public function __construct(EntityManagerInterface $entityManager,
                                BlockRepository $blockRepository,
                                ScoreManager $scoreManager)
    {
        $this->entityManager = $entityManager;
        $this->blockRepository = $blockRepository;
        $this->scoreManager = $scoreManager;
    }

    public function setBlockValue($blockCount, $block, $maxNumber)
    {
        $newValue   = $blockCount > $maxNumber ? $maxNumber : $blockCount;
        if ($newValue < $block->getNumber()) {
            $newValue = $block->getNumber();
        }

        if ($newValue === $maxNumber) {
            $block->setNumber(0);

            $this->scoreManager->addPoint(1);
            $this->scoreManager->addMultiplicator(1);
        } else {
            if ($newValue !== $block->getNumber()) {
                $this->scoreManager->addPoint(1);
            }
            $block->setNumber($newValue);
        }
    }

    public function createBlocks($blockIds, $maxNumber)
    {
        $blocks = [];
        foreach ($blockIds as $blockId) {
            $block      = $this->blockRepository->find($blockId);
            $blocks[$block->getX() . '-' . $block->getY()] = $block;
            $blockCount = count($blockIds);
            $this->setBlockValue($blockCount, $block, $maxNumber);

            $this->entityManager->persist($block);
        }
        return $blocks;
    }

}

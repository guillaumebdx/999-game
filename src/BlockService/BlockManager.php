<?php


namespace App\BlockService;


use App\Entity\Block;
use App\Entity\Matrice;
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

    public function addUncheckedBlock($inDbBlocks, $blocks, $multiple)
    {
        for ($i=0; $i<$multiple**2; $i++) {
            if (!in_array($inDbBlocks[$i], $blocks)) {
                $blocks[$inDbBlocks[$i]->getX() . '-' . $inDbBlocks[$i]->getY()] = $inDbBlocks[$i];
            }
        }
        return $blocks;
    }

    public function makeItFall(Matrice $matrice, $maxNumber, $blocks): void
    {
        for ($i=0; $i<$matrice->getMultiple()**2; $i++) {
            for ($x=1; $x<=$matrice->getMultiple(); $x++ ) {
                for ($y=1; $y<=$matrice->getMultiple(); $y++) {
                    $currentBlock = $blocks[$x . '-' . $y];
                    if ($currentBlock->getNumber() == 0) {
                        if (isset($blocks[$x-1 . '-' . $y])) {
                            $aboveBlock = $blocks[$x-1 . '-' . $y];
                            $currentBlock->setNumber($aboveBlock->getNumber());
                            $aboveBlock->setNumber(0);
                            $this->entityManager->persist($aboveBlock);
                            $this->entityManager->persist($currentBlock);
                        } else {
                            if ($matrice->getIncrementNewBlock() < Matrice::MAX_INCREMENT) {
                                $currentBlock->setNumber(rand(1,$maxNumber -1));
                                $matrice->setIncrementNewBlock($matrice->getIncrementNewBlock()+1);
                            } else {
                                $currentBlock->setNumber(Matrice::EMPTY_BLOCK);
                            }
                            $this->entityManager->persist($currentBlock);

                        }

                    }
                }
            }
        }
    }

}

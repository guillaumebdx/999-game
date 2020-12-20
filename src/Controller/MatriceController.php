<?php

namespace App\Controller;

use App\BlockService\BlockManager;
use App\Entity\Block;
use App\Entity\Matrice;
use App\Form\MatriceType;
use App\Repository\BlockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MatriceController
 * @package App\Controller
 * @Route("/game")
 */
class MatriceController extends AbstractController
{
    /**
     * @Route("/matrice/{matrice}", name="matrice_form")
     */
    public function index(Matrice $matrice,
                          Request $request,
                          EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(MatriceType::class, $matrice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($matrice);
        }

        return $this->render('matrice/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/replay", name="replay")
     */
    public function replay(EntityManagerInterface $manager)
    {
        $matrice = new Matrice();
        $matrice->setUser($this->getUser());
        $matrice->setScore(0);
        $matrice->setName('5x5');
        $matrice->setMultiple(5);
        $manager->persist($matrice);
        for ($i=1; $i<=5; $i++) {
            for ($j=1; $j<=5; $j++) {
                $block = new Block();
                $block->setX($i);
                $block->setY($j);
                $block->setNumber(rand(1, Block::MAX_NUMBER -1));
                $block->setMatrice($matrice);
                $manager->persist($block);
            }
        }
        $manager->flush();
        return $this->redirectToRoute('matrice', ['matrice' => $matrice->getId()]);
    }

    /**
     * @Route("/display/{matrice}", name="matrice")
     */
    public function display(Matrice $matrice,
                            Request $request,
                            BlockRepository $blockRepository,
                            EntityManagerInterface $entityManager,
                            BlockManager $blockManager)
    {
        if ($matrice->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $blockIds = $request->get('blocks');
        if ($request->getMethod() === 'POST' && $blockIds) {
            $blocks = [];
            foreach ($blockIds as $blockId) {
                $block      = $blockRepository->find($blockId);
                $blocks[$block->getX() . '-' . $block->getY()] = $block;
                $blockCount = count($blockIds);
                $newValue   = $blockCount > Block::MAX_NUMBER ? Block::MAX_NUMBER : $blockCount;
                if ($newValue < $block->getNumber()) {
                    $newValue = $block->getNumber();
                }

                if ($newValue === Block::MAX_NUMBER) {
                    $block->setNumber(0);
                } else {
                    $block->setNumber($newValue);
                }
                $entityManager->persist($block);
            }

            $inDbBlocks = $blockRepository->findByMatrice($matrice);

            //get all unchecked blocks and add it in blocks
            for ($i=0; $i<$matrice->getMultiple()**2; $i++) {
                if (!in_array($inDbBlocks[$i], $blocks)) {
                    $blocks[$inDbBlocks[$i]->getX() . '-' . $inDbBlocks[$i]->getY()] = $inDbBlocks[$i];
                }
            }
            for ($i=0; $i<$matrice->getMultiple()**2; $i++) {
                for ($x=1; $x<=$matrice->getMultiple(); $x++ ) {
                    for ($y=1; $y<=$matrice->getMultiple(); $y++) {
                        $currentBlock = $blocks[$x . '-' . $y];
                        if ($currentBlock->getNumber() == 0) {
                            if (isset($blocks[$x-1 . '-' . $y])) {
                                $aboveBlock = $blocks[$x-1 . '-' . $y];
                                $currentBlock->setNumber($aboveBlock->getNumber());
                                $aboveBlock->setNumber(0);
                                $entityManager->persist($aboveBlock);
                                $entityManager->persist($currentBlock);
                            } else {
                                if ($matrice->getIncrementNewBlock() < Matrice::MAX_INCREMENT) {
                                    $currentBlock->setNumber(rand(1,Block::MAX_NUMBER -1));
                                    $matrice->setIncrementNewBlock($matrice->getIncrementNewBlock()+1);
                                } else {
                                    $currentBlock->setNumber(999);
                                }
                                $entityManager->persist($currentBlock);
                            }

                        }
                    }
                }
            }
            $entityManager->flush();
        }

        return $this->render('matrice/display.html.twig', [
           'matrice' => $matrice,
        ]);
    }
}



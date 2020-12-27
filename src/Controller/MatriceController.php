<?php

namespace App\Controller;

use App\BlockService\BlockManager;
use App\BlockService\ScoreManager;
use App\Entity\Block;
use App\Entity\Level;
use App\Entity\Matrice;
use App\Form\MatriceType;
use App\Repository\BlockRepository;
use App\Repository\LevelRepository;
use App\Repository\MatriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @Route("/replay/{isCarriere}", name="replay")
     *
     */
    public function replay($isCarriere,
                           EntityManagerInterface $manager,
                           LevelRepository $levelRepository)
    {
        $user = $this->getUser();
        $multiple  = 5;
        $maxNumber = 6;
        $matrice   = new Matrice();
        $matrice->setIsTraining(true);
        if ($isCarriere) {
            $matrice->setIsTraining(false);
            if (!$user->getLevel()) {
                $levelZero = $levelRepository->findOneByLevel(0);
                $user->setLevel($levelZero);
            }
            $multiple  = $user->getLevel()->getMultiple();
            $maxNumber = $user->getLevel()->getMaxNumber();
        }
        $matrice->setUser($user);
        $matrice->setScore(0);
        $matrice->setName($multiple . 'x' . $multiple);
        $matrice->setMultiple($multiple);
        $manager->persist($matrice);
        for ($i=1; $i<=$multiple; $i++) {
            for ($j=1; $j<=$multiple; $j++) {
                $block = new Block();
                $block->setX($i);
                $block->setY($j);
                $block->setNumber(rand(1, $maxNumber -1));
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
                            BlockManager $blockManager,
                            ScoreManager $scoreManager,
                            MatriceRepository $matriceRepository,
                            LevelRepository $levelRepository)
    {
        if ($matrice->getUser() !== $this->getUser() && !$this->getUser()->isAdmin()) {
            return $this->redirectToRoute('home');
        }

        $blockIds = $request->get('blocks');

        if ($request->getMethod() === 'POST' && $blockIds) {

            $maxNumber = $matrice->getIsTraining() ? Block::MAX_NUMBER : $matrice->getUser()->getLevel()->getMaxNumber();
            $blocks   = $blockManager->createBlocks($blockIds, $maxNumber);
            $matrice->setScore($matrice->getScore() + $scoreManager->getTotalScore());
            $entityManager->persist($matrice);
            $inDbBlocks = $blockRepository->findByMatrice($matrice);


            $multiple = $matrice->getMultiple();
            $blocks = $blockManager->addUncheckedBlock($inDbBlocks, $blocks, $multiple);
            $blockManager->makeItFall($matrice, $maxNumber, $blocks);
            $entityManager->flush();
            if (!$matrice->getIsTraining()) {
                $currentLevel = $this->getUser()->getLevel();
                if ($matrice->getScore() >= $currentLevel->getTarget()) {
                    $newLevel = $levelRepository->findOneByLevel($currentLevel->getLevel() +1);
                    $this->getUser()->setLevel($newLevel);
                    $entityManager->persist($this->getUser());
                    $entityManager->flush();
                    $this->addFlash('success', 'Niveau réussi, vous pouvez passer au suivant');
                    return $this->redirectToRoute('home');
                }
            }
        }
        return $this->render('matrice/display.html.twig', [
            'matrice' => $matrice,
            'best_matrice' => $matriceRepository->findOneBy([], ['isTraining' => 'DESC', 'score' => 'DESC']),
        ]);
    }
}



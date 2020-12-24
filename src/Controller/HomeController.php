<?php

namespace App\Controller;

use App\Repository\LevelRepository;
use App\Repository\MatriceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(MatriceRepository $matriceRepository,
                          LevelRepository $levelRepository): Response
    {
        $user = $this->getUser();
        $maxLevel = $levelRepository->findOneBy([], ['level' => 'DESC']);
        $matrices = $matriceRepository->findBy(['user' => $user, 'isTraining' => true], ['createdAt' => 'DESC']);

        return $this->render('home/index.html.twig', [
            'matrices' => $matrices,
            'max_level' => $maxLevel->getLevel(),
        ]);
    }

    /**
     * @Route("/ranking", name="ranking")
     */
    public function ranking(MatriceRepository $matriceRepository)
    {
        $matrices = $matriceRepository->findBy(['isTraining' => true], ['score' => 'DESC'], 50);
        return $this->render('home/ranking.html.twig', [
            'matrices' => $matrices,
        ]);
    }
}

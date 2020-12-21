<?php

namespace App\Controller;

use App\Repository\MatriceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(MatriceRepository $matriceRepository): Response
    {
        $user = $this->getUser();
        $matrices = $matriceRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);
        return $this->render('home/index.html.twig', [
            'matrices' => $matrices,
        ]);
    }

    /**
     * @Route("/ranking", name="ranking")
     */
    public function ranking(MatriceRepository $matriceRepository)
    {
        $matrices = $matriceRepository->findBy([], ['score' => 'DESC'], 50);
        return $this->render('home/ranking.html.twig', [
            'matrices' => $matrices,
        ]);
    }
}

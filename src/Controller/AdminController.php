<?php

namespace App\Controller;

use App\Repository\MatriceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(UserRepository $userRepository, MatriceRepository $matriceRepository): Response
    {
        $bestMatrice = $matriceRepository->findOneBy([], ['score' => 'ASC']);
        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
            'best_matrice' => $bestMatrice,
        ]);
    }
}

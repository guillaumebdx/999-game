<?php

namespace App\Controller;

use App\Entity\Level;
use App\Repository\LevelRepository;
use App\Repository\MatriceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(UserRepository $userRepository, MatriceRepository $matriceRepository, LevelRepository $levelRepository): Response
    {
        $bestMatrice = $matriceRepository->findOneBy(['isTraining' => true], ['score' => 'DESC']);

        return $this->render('admin/index.html.twig', [
            'users' => $userRepository->findAll(),
            'best_matrice' => $bestMatrice,
            'levels' => $levelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/level/{level}", name="level")
     */
    public function updateLevel(Level $level, EntityManagerInterface $entityManager)
    {
        $this->getUser()->setLevel($level);
        $entityManager->persist($this->getUser());
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }
}

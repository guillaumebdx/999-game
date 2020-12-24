<?php

namespace App\Controller;

use App\Entity\Level;
use App\Form\LevelType;
use App\Repository\LevelRepository;
use App\Repository\MatriceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("switch/level/{level}", name="level")
     */
    public function switchLevel(Level $level, EntityManagerInterface $entityManager)
    {
        $this->getUser()->setLevel($level);
        $entityManager->persist($this->getUser());
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/create/level", name="create_level")
     */
    public function createLevel(Request $request,
                                EntityManagerInterface $entityManager,
                                LevelRepository $levelRepository)
    {
        $lastLevel = $levelRepository->findOneBy([],['level' => 'DESC']);

        $level = new Level();
        $level->setLevel($lastLevel->getLevel() +1);
        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($level);
            $entityManager->flush();
            return $this->redirectToRoute('admin_index');
        }
        return $this->render('level/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/level/{level}", name="update_level")
     */
    public function updateLevel(Level $level,
                                EntityManagerInterface $entityManager,
                                Request $request)
    {
        $form = $this->createForm(LevelType::class, $level);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($level);
            $entityManager->flush();
            return $this->redirectToRoute('admin_index');
        }
        return $this->render('level/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Matrice;
use App\Form\MatriceType;
use App\Repository\BlockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatriceController extends AbstractController
{
    /**
     * @Route("/matrice/{matrice}", name="matrice")
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
     * @Route("/display/{matrice}", name="matrice")
     */
    public function display(Matrice $matrice,
                            Request $request,
                            BlockRepository $blockRepository,
                            EntityManagerInterface $entityManager)
    {
        $blockIds = $request->get('blocks');
        if ($request->getMethod() === 'POST' && $blockIds) {


            foreach ($blockIds as $blockId) {
                $block      = $blockRepository->find($blockId);
                $blockCount = count($blockIds);
                $newValue   = $blockCount > $matrice->getMultiple() ? $matrice->getMultiple() : $blockCount;
                $block->setNumber($newValue);
                $entityManager->persist($block);
            }
            $entityManager->flush();
        }

        return $this->render('matrice/display.html.twig', [
           'matrice' => $matrice,
        ]);
    }
}



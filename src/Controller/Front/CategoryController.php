<?php

namespace App\Controller\Front;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/women", name="app_front_category_women")
     */
    public function women(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findBy(['gender' => 'Unisex', 'gender' => 'Women']);

        return $this->render('front/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/men", name="app_front_category_men")
     */
    public function men(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy(['gender' => 'Unisex', 'gender' => 'Men']);
        
        return $this->render('front/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/kids", name="app_front_category_kids")
     */
    public function kids(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy(['gender' => 'Unisex', 'gender' => 'Kids']);
        
        return $this->render('front/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}

<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/women", name="app_front_category_women")
     */
    public function women(): Response
    {
        return $this->render('front/category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/men", name="app_front_category_men")
     */
    public function men(): Response
    {
        return $this->render('front/category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    /**
     * @Route("/kids", name="app_front_category_kids")
     */
    public function kids(): Response
    {
        return $this->render('front/category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}

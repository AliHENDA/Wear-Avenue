<?php

namespace App\Controller\Front;

use App\Service\Mail;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_front_main_home")
     */
    public function index(ProductRepository $productRepository): Response
    { 
        $products = $productRepository->findAll();
        $latestProducts = array_slice($products, -4);

        return $this->render('front/main/home.html.twig', [
            'latestProducts' => $latestProducts,
            'bestSellersProducts' => $productRepository->getBestSellers()
        ]);
    }

}

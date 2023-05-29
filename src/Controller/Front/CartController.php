<?php

namespace App\Controller\Front;

use App\Entity\Inventory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    /**
     * @Route("/cart/add/{id<\d+>}", name="app_cart_add")
     */
    public function add(Inventory $inventory): Response
    {
        
        return $this->render('cart/index.html.twig', [
            'product' => $inventory->getProduct(),
        ]);
    }
}

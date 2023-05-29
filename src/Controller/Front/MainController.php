<?php

namespace App\Controller\Front;

use App\Entity\Product;
use App\Repository\InventoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_front_main_home")
     */
    public function index(): Response
    {
        return $this->render('front/main/home.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/product/{id}", name="app_front_product_item")
     */
    public function productAction(ProductRepository $productRepository, $id): Response
    {
        $product = $productRepository->find($id);
        $inventories = $product->getInventories();

        return $this->render('front/main/product.html.twig', [
            'product' => $product,
            'inventories' => $inventories
        ]);
    }

    /**
     * @Route("/product/choiced/{id}", name="app_front_product_choiced", methods={"GET", "POST"})
     */
    public function add($id, InventoryRepository $inventoryRepository): Response
    {
        
        $inventory = $inventoryRepository->find($id);
        $product =$inventory->getProduct();
        dd($inventory);

        return $this->render('front/main/a.html.twig', [
            'product' => $product,
        ]);
    }

}

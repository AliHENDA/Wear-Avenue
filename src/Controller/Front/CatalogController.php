<?php

namespace App\Controller\Front;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatalogController extends AbstractController
{
    /**
     * @Route("/women", name="app_front_category_women")
     */
    public function women(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();

        return $this->render('front/catalog/category.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/women/{slug}", name="app_front_products_by_category_women")
     */
    public function womenProductsByCategory(Category $category): Response
    {   
        $products = $category->getWomenProducts();
        $name = $category->getName();

        return $this->render('front/catalog/products.html.twig', [
            'products' => $products,
            'name' => $name
        ]);
    }

    /**
     * @Route("/men", name="app_front_category_men")
     */
    public function men(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        
        return $this->render('front/catalog/category.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/men/{slug}", name="app_front_products_by_category_men")
     */
    public function menProductsByCategory(Category $category): Response
    {   

        $products = $category->getMenProducts();
        $name = $category->getName();
        
        return $this->render('front/catalog/products.html.twig', [
            'products' => $products,
            'name' => $name
        ]);
    }

    /**
     * @Route("/kids", name="app_front_category_kids")
     */
    public function kids(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        
        return $this->render('front/catalog/category.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/kids/{slug}", name="app_front_products_by_category_kids")
     */
    public function kidsProductsByCategory(Category $category): Response
    {   
        $products = $category->getKidsProducts();
        $name = $category->getName();

        return $this->render('front/catalog/products.html.twig', [
            'products' => $products,
            'name' => $name
        ]);
    }
    
    /**
     * @Route("/product/{slug}", name="app_front_product")
     */
    public function getProduct(Product $product): Response
    {   
        return $this->render('front/catalog/product.html.twig', [
            'product' => $product,
        ]);
    }
}

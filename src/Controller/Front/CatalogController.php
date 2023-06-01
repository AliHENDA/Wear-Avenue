<?php

namespace App\Controller\Front;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
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

        return $this->render('front/catalog/category.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/women/{slug}", name="app_front_products_by_category_women")
     */
    public function womenProductsByCategory(Category $category = null): Response
    {   

        $this->noExist($category);

        $products = $category->getWomenProducts();

        return $this->render('front/catalog/products.html.twig', [
            'products' => $products,
            'category' => $category
        ]);
    }

    /**
     * @Route("/men", name="app_front_category_men")
     */
    public function men(CategoryRepository $categoryRepository): Response
    {
        
        return $this->render('front/catalog/category.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/men/{slug}", name="app_front_products_by_category_men")
     */
    public function menProductsByCategory(Category $category = null): Response
    {   
        $this->noExist($category);

        $products = $category->getMenProducts();
        
        return $this->render('front/catalog/products.html.twig', [
            'products' => $products,
            'category' => $category
        ]);
    }

    /**
     * @Route("/kids", name="app_front_category_kids")
     */
    public function kids(CategoryRepository $categoryRepository): Response
    {
        
        return $this->render('front/catalog/category.html.twig', [
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/kids/{slug}", name="app_front_products_by_category_kids")
     */
    public function kidsProductsByCategory(Category $category = null): Response
    {   
        $this->noExist($category);

        $products = $category->getKidsProducts();

        return $this->render('front/catalog/products.html.twig', [
            'products' => $products,
            'category' => $category
        ]);
    }

    /**
     * @Route("/products", name="app_front_products")
     */
    public function getAllProducts(ProductRepository $productRepository): Response
    {   
        return $this->render('front/catalog/products.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/product/{slug}", name="app_front_product")
     */
    public function getProduct(Product $product = null): Response
    {   
        $this->noExist($product);

        return $this->render('front/catalog/product.html.twig', [
            'product' => $product,
        ]);
    }


    private function noExist($object) 
    {
        if($object === null) {
            throw $this->createNotFoundException("It doesn't exist");
        }
    }
}

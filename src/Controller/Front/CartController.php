<?php

namespace App\Controller\Front;

use App\Entity\Cart;
use DateTimeImmutable;
use App\Entity\Inventory;
use App\Repository\CartRepository;
use App\Repository\InventoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(): Response
    {
        if($this->getUser()) {
            $user = $this->getUser();
            $cart = $user->getCarts();
        } else {
        $cart = null;
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route("/cart/secure/add/{id<\d+>}", name="app_cart_add", methods={"POST"})
     */
    public function add(Inventory $inventory, CartRepository $cartRepository, ManagerRegistry $doctrine, InventoryRepository $inventoryRepository): Response
    {

        $existingCart = $cartRepository->findOneBy(['user' => $this->getUser(), 'inventoryItem' => $inventory]);

        if($existingCart) {

        $existingCart->setQuantity($existingCart->getQuantity() + 1);
        $existingCart->setUpdatedAt(new DateTimeImmutable());

        $entityManager = $doctrine->getManager();
        $entityManager->flush();

        } else {
        
            $cart = new Cart();
            $cart->setUser($this->getUser());
            $cart->setInventoryItem($inventory);
            $cart->setQuantity(1);
        
            $cartRepository->add($cart, true);
        }
        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/secure/delete/{id<\d+>}", name="app_cart_delete")
     */
    public function deleteItem(Inventory $inventory, CartRepository $cartRepository, ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();
        $existingCart = $cartRepository->findOneBy(['user' => $this->getUser(), 'inventoryItem' => $inventory]);

        if($existingCart->getQuantity() > 1) {

        $existingCart->setQuantity($existingCart->getQuantity() - 1);
        $existingCart->setUpdatedAt(new DateTimeImmutable());

        } else {
        
            $entityManager->remove($existingCart);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/secure/empty}", name="app_cart_empty")
     */
    public function empty(Inventory $inventory, CartRepository $cartRepository, ManagerRegistry $doctrine): Response
    {
        
       // on récupère l'utilisateur connecté
        $user = $this->getUser();
        
        // on récupère tous les objets cart fonction de l'utilisateur
        $existingCarts = $cartRepository->findBy(["user"=> $user]);
        
        foreach ($existingCarts as $existingCart) {

            // pour chaque objet cart, on indique qu'on veut le supprimer, et on le flush
            $entityManager = $doctrine->getManager();
            $entityManager->remove($existingCart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cart');
    }
}

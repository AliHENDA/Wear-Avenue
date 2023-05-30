<?php

namespace App\Controller\Front;

use App\Entity\Order;
use DateTimeImmutable;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="app_front_order")
     */
    public function index(Request $request): Response
    {
        
        $cart = $this->getUser()->getCarts();

        if(!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('app_front_address_add');
        }
        $form = $this->createForm(OrderType::class, null, ['user' => $this->getUser()]);

        return $this->render('front/order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/order/summary", name="app_front_order_recap", methods={"POST"})
     */
    public function add(Request $request, ManagerRegistry $doctrine, OrderRepository $orderRepository): Response
    {
        $date = new DateTimeImmutable();
        $cart = $this->getUser()->getCarts();

        $existingOrder = $orderRepository->findOneBy(['state' => 0, 'user' => $this->getUser()]);

        if($existingOrder) {
            $reference = $existingOrder->getReference();
            return $this->redirectToRoute('app_stripe', ['reference' => $reference]);
        }

        if(!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('app_front_address_add');
        }
        $form = $this->createForm(OrderType::class, null, ['user' => $this->getUser()]);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $carrier = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $deliveryContent = $delivery->getFirstname().' '.$delivery->getLastname();

            if ($delivery->getCompany()) {
                $deliveryContent .= '<br>'.$delivery->getCompany();
            }

            $deliveryContent .= '<br>'.$delivery->getAddress();
            $deliveryContent .= '<br>'.$delivery->getPostal().' '.$delivery->getCity();
            $deliveryContent .= '<br>'.$delivery->getCountry();
            $deliveryContent .= '<br>'.$delivery->getPhone();

            $entityManager = $doctrine->getManager();
            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCarrierName($carrier->getName());
            $order->setCarrierPrice($carrier->getPrice());
            $order->setDeliveryAddress($deliveryContent);
            $order->setCreatedAt($date);
            
            foreach($cart as $cartItem) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($cartItem->getInventoryItem()->getProduct()->getName());
                $orderDetails->setQuantity($cartItem->getQuantity());
                $orderDetails->setSize($cartItem->getInventoryItem()->getSize());
                $orderDetails->setPrice($cartItem->getInventoryItem()->getProduct()->getPrice());
                $orderDetails->setTotal($cartItem->getInventoryItem()->getProduct()->getPrice() * $cartItem->getQuantity());
                
                $entityManager->persist($orderDetails);
            }
 
            $entityManager->flush();

           return $this->render('front/order/add.html.twig', [
            'cart' => $cart,
            'carrier'=> $carrier,
            'delivery' => $deliveryContent,
            'reference' => $order->getReference()
           ]);
        }
        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/order/thank-you-for-your-order/{stripe_session_id}", name="app_front_order_success")
     */
    public function orderSuccess($stripe_session_id, CartRepository $cartRepository, OrderRepository $orderRepository, EntityManagerInterface $em): Response
    {
        $order = $orderRepository->findOneBy(['stripe_session_id' => $stripe_session_id]);

        if(!$order) {
            return $this->redirectToRoute('app_front_main_home');
        }

        $user = $order->getUser();
        $cart = $user->getCarts();
        

        foreach($cart as $cartItem) {
            

            $quantity = $cartItem->getQuantity();
            $inventoryItem = $cartItem->getInventoryItem();
            $currentStock = $inventoryItem->getStock();
            $inventoryItem->setStock($currentStock - $quantity);

            $em->flush();

            $cartRepository->remove($cartItem, true);
        }

        $order->setState(true);

        $em->flush();

        return $this->render('front/order/success.html.twig', [
        ]);
    }

    /**
     * @Route("/order/erreur/{stripe_session_id}", name="app_front_order_cancel")
     */
    public function orderFailure($stripe_session_id, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findOneBy(['stripe_session_id' => $stripe_session_id]);

        $orders = $order->getOrderDetails();

        foreach($orders as $orderDetails) {
            $order->removeOrderDetail($orderDetails);
        }

        $orderRepository->remove($order, true);

        if(!$order) {
            return $this->redirectToRoute('app_front_main_home');
        }

        return $this->render('front/order/failure.html.twig', [
           'order' => $order
        ]);
    }
    
}

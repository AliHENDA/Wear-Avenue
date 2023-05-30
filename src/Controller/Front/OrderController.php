<?php

namespace App\Controller\Front;

use DateTimeImmutable;
use App\Form\OrderType;
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
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $date = new DateTimeImmutable();
        $cart = $this->getUser()->getCarts();

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

            foreach($cart as $cartItem) {
            
                $cartItem->setCarrierName($carrier->getName());
                $cartItem->setCarrierPrice($carrier->getPrice());
                $cartItem->setDeliveryAddress($deliveryContent);
            }
 
            $entityManager->flush();

           return $this->render('front/order/add.html.twig', [
            'cart' => $cart,
            'carrier'=> $carrier,
            'delivery' => $deliveryContent,
           ]);
        }
        return $this->redirectToRoute('app_cart');
    }
    
}

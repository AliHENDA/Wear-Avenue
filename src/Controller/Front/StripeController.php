<?php

namespace App\Controller\Front;

use Stripe\Stripe;
use App\Entity\Cart;
use App\Entity\Order;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/order/create-session/{reference}", name="app_stripe")
     */
    public function index($reference, EntityManagerInterface $em): Response
    {   

        $order = $em->getRepository(Order::class)->findOneBy(['reference' => $reference]);

        if(!$order) {
            return $this->redirectToRoute('app_front_order');
        }

        $product_for_stripe =[];
        $YOUR_DOMAIN = 'http://localhost:8000';
        
        //ici $product correspond à un objet orderDetails
        foreach($order->getOrderDetails() as $orderDetails) {

            $product_for_stripe[] = [
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                'price_data' => [
                    'currency' => 'EUR',
                    'unit_amount' => $orderDetails->getPrice(),
                    'product_data' => [
                        'name' => $orderDetails->getProduct(),
                    ]
                ],
                'quantity' => $orderDetails->getQuantity(),
            ];
        }

            $product_for_stripe[] = [
                # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                'price_data' => [
                    'currency' => 'EUR',
                    'unit_amount' => $order->getCarrierPrice(),
                    'product_data' => [
                        'name' => $order->getCarrierName(),
                        'images' => [$YOUR_DOMAIN]
                    ]
                ],
                'quantity' => 1,
            ];





        $stripeSecretKey = "sk_test_51N8KXpHhr9meOSvgHdYYjf07seQdhWtcp2XA7wbYOOdVGCNqye2M64QO0KbPTYmSmKrzU8h665kZEaQaq9W4W1iC00Cz7x4s4j";
            Stripe::setApiKey($stripeSecretKey);

            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
              'line_items' => [
                $product_for_stripe
                ],
              'mode' => 'payment',
              'success_url' => $YOUR_DOMAIN . '/order/thank-you-for-your-order/{CHECKOUT_SESSION_ID}',
              'cancel_url' => $YOUR_DOMAIN . '/order/erreur/{CHECKOUT_SESSION_ID}',
            ]);

            $order->setStripeSessionId($checkout_session->id);
            $em->flush();

            // header("HTTP/1.1 303 See Other");
            // header("Location: " . $checkout_session->url);
            
            return $this->redirect($checkout_session->url);
    }
}

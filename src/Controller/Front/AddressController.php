<?php

namespace App\Controller\Front;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    /**
     * @Route("/my-account/adresses", name="app_front_address")
     */
    public function index(): Response
    {

        return $this->render('front/address/index.html.twig', [
            'controller_name' => 'AddressController',
        ]);
    }

    /**
     * @Route("/my-account/add-address", name="app_front_address_add")
     */
    public function add(Request $request, AddressRepository $addressRepository): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $address->setUser($this->getUser());
            $addressRepository->add($address, true);

        if($this->getUser()->getCarts() == true) {
            return $this->redirectToRoute('app_front_order');
        } else {
            return $this->redirectToRoute('app_front_address');
        }
        }
        return $this->render('front/address/add.html.twig', [
            'address' => $address,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/my-account/change-address/{id}", name="app_front_address_edit")
     */
    public function edit(Request $request, AddressRepository $addressRepository, $id): Response
    {
        $address = $addressRepository->find($id);

        if(!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_front_address');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $addressRepository->add($address, true);

            return $this->redirectToRoute('app_front_address');
        }
        return $this->render('front/address/add.html.twig', [
            'address' => $address,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/my-account/delete-address/{id}", name="app_front_address_delete")
     */
    public function delete(AddressRepository $addressRepository, $id): Response
    {
        $address = $addressRepository->find($id);

        if(!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_front_address');
        }
            
        $addressRepository->remove($address, true);

        return $this->redirectToRoute('app_front_address');
    }
}


<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="app_front_register")
     */
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);

            $userRepository->add($user, true);

            return $this->redirectToRoute('app_front_main_home');
        }

        return $this->renderForm('register/index.html.twig', [
            'form' => $form,
        ]);
    }
}

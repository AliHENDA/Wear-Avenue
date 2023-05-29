<?php

namespace App\Controller\Front;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/my-account", name="app_front_account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route("/mon-compte/modifier-mot-de-passe", name="app_front_account_password")
     */
    public function edit(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): Response
    {   
        $message = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $oldPassword = $form->get('old_password')->getData();

            if($passwordHasher->isPasswordValid($user, $oldPassword)) {
                $newPassword = $form->get('new_password')->getData();
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $newPassword
                );
                $user->setPassword($hashedPassword);

                $userRepository->add($user, true);
                $message = "Votre message a été mis à jour";

                return $this->redirectToRoute('app_account');
;           } else {
                $message = "Votre mot de passe actuel est incorrect";
            }   
            
        }

        return $this->renderForm('account/password.html.twig', [
            'form' => $form,
            'message' => $message
        ]);
    }
}

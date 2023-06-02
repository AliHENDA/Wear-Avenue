<?php

namespace App\Controller;

use App\Service\Mail;
use DateTimeImmutable;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ResetPasswordRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/reset-password", name="app_reset_password")
     */
    public function index(Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        if($this->getUser()) {
            return $this->redirectToRoute('app_front_main_home');
        }

        if($request->get('email')){
            $user = $userRepository->findOneByEmail($request->get('email'));
    
            
            if($user){
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new DateTimeImmutable());

                $em->persist($resetPassword);
                $em->flush();

                $url = $this->generateUrl('app_update_password', ['token' => $resetPassword->getToken()]);

                $content = '<a href="'.$url.'">Reset your password</a>';
                
                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getFirstname(), 'Reset your password', $content);
                $this->addFlash('notice', 'Vous allez recevoir un mail');

               return $this->redirectToRoute('app_update_password', ['token' => $resetPassword->getToken()]);
            } else {
                $this->addFlash('notice', 'This email is unknown');
            }
        }
        return $this->render('reset_password/index.html.twig');
    }

    /**
     * @Route("/reset-password/{token}", name="app_update_password")
     */
    public function reset($token, ResetPasswordRepository $resetPasswordRepository, Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository)
    {
        $resetPassword = $resetPasswordRepository->findOneByToken($token);

        if(!$resetPassword){
            return $this->redirectToRoute('app_reset_password');
        }

        $now = new DateTimeImmutable();

        if($now > $resetPassword->getCreatedAt()->modify('+ 3 hour')){

            $this->addFlash('notice', 'Your password has expired. Please try again');
            return $this->redirectToRoute('app_reset_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user = $resetPassword->getUser();
            $newPassword = $form->get('new_password')->getData();
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $newPassword
                );
                $user->setPassword($hashedPassword);

                $userRepository->add($user, true);
                $resetPasswordRepository->remove($resetPassword, true);

                $this->addFlash('notice', 'Your password has been updated');
                
            
            return $this->redirectToRoute('app_login');

        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);

        
    }
}

<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            }
        }
        return $this->render('reset_password/index.html.twig');
    }
}

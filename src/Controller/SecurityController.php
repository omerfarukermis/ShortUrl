<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('home');
         }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if($lastUsername){
            return $this->render('home/about.html.twig', ['last_username' => $lastUsername]);
        }
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * @Route("/signup", name="app_signup")
     */
    public function signup(Request $request,UserPasswordEncoderInterface $encoder): Response{


        var_dump($request->request->all());
        exit();
        $user = new User();

        $encoded = $encoder->encodePassword($user, '123456');

        $user->setEmail('volkan.sengul@test.com')
            ->setPassword($encoded)
            ->setRoles(['ROLE_USER'])
            ->setIsActive(1);

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        return new Response('User created !');

    }
}

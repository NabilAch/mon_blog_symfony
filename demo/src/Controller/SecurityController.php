<?php

namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
Use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /** 
     * @Route("/inscription", name="security_registration")
    */
   public function registration(Request $request, EntityManagerInterface $manager, 
   UserPasswordEncoderInterface $encoder){
        $user = new User();

       $form = $this->createForm(RegistrationType::class, $user);

       $form->handleRequest($request);

       if($form->isSubmitted()&& $form->isValid()) {
           $hash = $encoder->encodePassword($user, $user->getPassword());

           $user->setPassword($hash);

           $manager->persist($user);
           $manager->flush();
       }

       return $this->render('security/registration.html.twig', [
           'form' => $form->createView()
       ]);


   }
}
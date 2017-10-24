<?php

namespace UserBundle\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

class RegisterController extends Controller
{
    public function formAction()
    {
    	$user = new User();
    	$form = $this->createForm(UserType::class, $user);
    	
        return $this->render('UserBundle:Auth:register.html.twig', array('form'=>$form->createView()
            // ...
        ));
    }
    public function registerAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$user = new User();
    	$form = $this->createForm(UserType::class, $user);
    	$form->handleRequest($request);

       if ($form->isSubmitted() && $form->isvalid()) {
       		$encoder = $this->get('security.password_encoder');
    		$user->setPassword($encoder->encodePassword($user, $user->getPassword()));
    		$em->persist($user);
    		$em->flush();
    		return $this->redirectToRoute('user.login.form');

    	}
    	// return $this->redirectToRoute('register');
    }

}

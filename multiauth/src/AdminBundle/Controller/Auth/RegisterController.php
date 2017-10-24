<?php

namespace AdminBundle\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Entity\Admin;
use AppBundle\Form\AdminType;

class RegisterController extends Controller
{
    public function formAction()
    {
    	$user = new Admin();
    	$form = $this->createForm(AdminType::class, $user);
    	
        return $this->render('AdminBundle:Auth:register.html.twig', array('form'=>$form->createView()
            // ...
        ));
    }
    public function registerAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$user = new Admin();
    	$form = $this->createForm(AdminType::class, $user);
    	$form->handleRequest($request);

       if ($form->isSubmitted() && $form->isvalid()) {
       		$encoder = $this->get('security.password_encoder');
    		$user->setPassword($encoder->encodePassword($user, $user->getPassword()));
    		$em->persist($user);
    		$em->flush();
    		return $this->redirectToRoute('admin.login.form');

    	}
    	// return $this->redirectToRoute('register');
    }

}

<?php

namespace UserBundle\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use UserBundle\Entity\User;

class LoginController extends Controller
{
	public function formAction(Request $request){

		$helper = $this->get('security.authentication_utils');
        return $this->render('UserBundle:Auth:login.html.twig', array(
        	'error'        => $helper->getLastAuthenticationError(),
            'last_username' => $helper->getLastUsername()
        ));
	}
	public function loginAction(Request $request){
		$helper = $this->get('security.authentication_utils');
        return $this->render('UserBundle:Auth:login.html.twig', array(
        	'error'        => $helper->getLastAuthenticationError(),
            'last_username' => $helper->getLastUsername()
        ));
	}
	public function logoutAction(){

    }
    

}

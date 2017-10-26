<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Admin;

class RoleController extends Controller
{
    public function indexAction()
    {
    	$repository = $this->getDoctrine()->getRepository(Admin::class);
    	$admins = $repository->findAll();
    	// var_dump($admins);
    	// exit();
        return $this->render('AdminBundle:Role:list.html.twig', array(
            'admins' => $admins
        ));
    }
    public function addAction(){

    }

}

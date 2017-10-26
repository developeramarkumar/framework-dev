<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use AppBundle\Entity\Admin;
class PermissionController extends Controller
{
    public function indexAction()
    {
    	$repository = $this->getDoctrine()->getRepository(Admin::class);
    	$admins = $repository->findAll();
    	// var_dump($admins);
    	// exit();
        return $this->render('AdminBundle:Permission:list.html.twig', array(
            'admins' => $admins
        ));
    }
    public function addAction(){
        
    }

}

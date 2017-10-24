<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        return $this->render('UserBundle:Default:dashboard.html.twig', array(
            // ...
        ));
    }

}

<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:dashboard.html.twig', array(
            // ...
        ));
    }

}

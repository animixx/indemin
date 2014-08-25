<?php

namespace Eye3\ControlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('Eye3ControlBundle:Default:index.html.twig');
    }
}

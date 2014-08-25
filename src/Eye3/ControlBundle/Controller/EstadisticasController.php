<?php

namespace Eye3\ControlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class EstadisticasController extends Controller
{
    /**
     * @Route("/tiempo")
     * @Template()
     */
    public function tiempoAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/camion")
     * @Template()
     */
    public function camionAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/grua")
     * @Template()
     */
    public function gruaAction()
    {
        return array(
                // ...
            );    }

}

<?php

namespace Eye3\ControlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eye3\ControlBundle\Entity\Datos;

class EstadisticasController extends Controller
{
    /**
     * @Route("/tiempo")
     * @Template()
     */
    public function tiempoAction()
    {
		$em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('Eye3ControlBundle:Datos')->tiempo_dia();

        return array(
                'entities' => $entities,
            );    
			
	}

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

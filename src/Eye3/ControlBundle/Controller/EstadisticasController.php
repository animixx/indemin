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

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->tiempo_dia();

        return array(
                'datos' => $datos,
            );    
			
	}

    /**
     * @Route("/camion")
     * @Template()
     */
    public function camionAction()
    {
        $em = $this->getDoctrine()->getManager();

		$camiones = $em->getRepository('Eye3ControlBundle:Datos')->camiones();
        $datos = $em->getRepository('Eye3ControlBundle:Datos')->camion_dia();

        return array(
                'datos' => $datos,
                'camiones' => $camiones,
            );    
		
	}

    /**
     * @Route("/grua")
     * @Template()
     */
    public function gruaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->grua_dia();

        return array(
                'datos' => $datos,
            ); 
     }

}

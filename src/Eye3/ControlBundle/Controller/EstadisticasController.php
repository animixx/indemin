<?php

namespace Eye3\ControlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eye3\ControlBundle\Entity\Datos;
use Symfony\Component\HttpFoundation\Request;

class EstadisticasController extends Controller
{
    /**
     * @Route("/tiempo")
     * @Template()
     */
    public function tiempoAction(Request $request)
    {
		$fecha='16-06-2014';
		 if ($request->getMethod() == 'POST' and $request->request->get('fecha') != "" ) {
		 $fecha = $request->request->get('fecha');
		
		  }
		 
		$em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->tiempo_dia($fecha);

        return array(
				'titulo' => 'Diario',
                'datos' => $datos,
				'fecha' => $fecha,
				'adicional' => '',
				'formato' => 'dd-mm-yyyy',
				'inicio' => '13-08-2013',
            );    
			
	}
	
    /**
     * @Route("/tiempo/semanal")
     * @Template("Eye3ControlBundle:Estadisticas:tiempo.html.twig")
     */
    public function tiempoSemanaAction(Request $request)
    {
		$fecha='16-06-2014';
		 if ($request->getMethod() == 'POST' and $request->request->get('fecha') != "" ) {
		 $fecha = $request->request->get('fecha');
		
		  }
		 
		$em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->tiempo_semana($fecha);

        return array(
				'titulo' => 'Semanal',
                'datos' => $datos,
				'fecha' => $fecha,
				'adicional' => 'selectWeek:true,',
				'formato' => 'dd-mm-yyyy',
				'inicio' => '13-08-2013',
            );    
			
	}
	
    /**
     * @Route("/tiempo/mensual")
     * @Template("Eye3ControlBundle:Estadisticas:tiempo.html.twig")
     */
    public function tiempoMesAction(Request $request)
    {
		
		 if ($request->getMethod() == 'POST' and $request->request->get('fecha') != "" ) {
		 $fecha = $request->request->get('fecha');
			} 
		else $fecha='06-2014';
		 
		$em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->tiempo_mes($fecha);

        return array(
				'titulo' => 'Mensual',
                'datos' => $datos,
				'fecha' => $fecha,
				'adicional' => 'minViewMode: 1,',
				'formato' => 'mm-yyyy',
				'inicio' => '08-2013',
            );    
			
	}

    /**
     * @Route("/camion")
     * @Template()
     */
    public function camionAction(Request $request)
    {
		 $fecha='16-06-2014';
		 if ($request->getMethod() == 'POST' and $request->request->get('fecha') != "" ) {
		 $fecha = $request->request->get('fecha');
		
		  }
		  
        $em = $this->getDoctrine()->getManager();

		$camiones = $em->getRepository('Eye3ControlBundle:Datos')->camiones();
        $datos = $em->getRepository('Eye3ControlBundle:Datos')->camion_dia($fecha);

        return array(
                'datos' => $datos,
                'camiones' => $camiones,
				'fecha' => $fecha,
            );    
		
	}
	
    /**
     * @Route("/camion/semanal")
     * @Template()
     */
    public function camionSemanaAction(Request $request)
    {
		 $fecha='16-06-2014';
		 if ($request->getMethod() == 'POST' and $request->request->get('fecha') != "" ) {
		 $fecha = $request->request->get('fecha');
		
		  }
		
        $em = $this->getDoctrine()->getManager();

		$camiones = $em->getRepository('Eye3ControlBundle:Datos')->camiones();
        $datos = $em->getRepository('Eye3ControlBundle:Datos')->camion_semana($fecha);

        return array(
                'datos' => $datos,
                'camiones' => $camiones,
				'fecha' => $fecha,
				'domingo' => date_create($fecha)->modify('last Sunday'),
            );    
	}
	
	/**
     * @Route("/camion/mensual")
     * @Template()
     */
    public function camionMesAction(Request $request)
    {
		 
		 if ($request->getMethod() == 'POST' and $request->request->get('fecha') != "" ) {
		 $fecha = $request->request->get('fecha');
		  }
		  else $fecha='06-2014';
		
        $em = $this->getDoctrine()->getManager();

		$camiones = $em->getRepository('Eye3ControlBundle:Datos')->camiones();
        $datos = $em->getRepository('Eye3ControlBundle:Datos')->camion_mes($fecha);

        return array(
                'datos' => $datos,
                'camiones' => $camiones,
				'fecha' => $fecha,
				'primer' => date_create('01-'.$fecha)->modify('next Monday'),
            );    
	}
	

    /**
     * @Route("/grua")
     * @Template()
     */
    public function gruaAction(Request $request)
    {
		 $fecha='16-06-2014';
		 if ($request->getMethod() == 'POST' and $request->request->get('fecha') != "" ) {
		 $fecha = $request->request->get('fecha');
		
		  }
		  
        $em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->grua_dia($fecha);

        return array(
                'datos' => $datos,
				'fecha' => $fecha,
            ); 
     }

}

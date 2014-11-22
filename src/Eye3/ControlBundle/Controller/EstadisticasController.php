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
		 if ($request->getMethod() == 'POST' and $request->request->get('fecha') != "" ) {
		 $fecha = $request->request->get('fecha');
		  }
		 else
			$fecha = date("d-m-Y");
		 
		$em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->tiempo_dia(date("Y-m-d", strtotime($fecha)));
		$desde = $em->getRepository('Eye3ControlBundle:Datos')->primer_dato();

        return array(
				'titulo' => 'Diario',
                'datos' => $datos,
				'fecha' => $fecha,
				'adicional' => '',
				'formato' => 'dd-mm-yyyy',
				'inicio' => reset($desde)['inicio'],
            );    
			
	}
	
    /**
     * @Route("/tiempo/semanal")
     * @Template("Eye3ControlBundle:Estadisticas:tiempo.html.twig")
     */
    public function tiempoSemanaAction(Request $request)
    {
		 if ($request->getMethod() == 'POST' and $request->request->get('fecha') != "" ) {
		 $fecha = $request->request->get('fecha');
		
		  }
		 else
			$fecha = date("d-m-Y");
			
		$em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->tiempo_semana(date("Y-m-d", strtotime($fecha)));
		$desde = $em->getRepository('Eye3ControlBundle:Datos')->primer_dato();

        return array(
				'titulo' => 'Semanal',
                'datos' => $datos,
				'fecha' => $fecha,
				'adicional' => 'selectWeek:true,',
				'formato' => 'dd-mm-yyyy',
				'inicio' => reset($desde)['inicio'],
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
		else $fecha = date("m-Y");
		 
		$em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->tiempo_mes(date("Y-m-d", strtotime("01-".$fecha)));
		$desde = $em->getRepository('Eye3ControlBundle:Datos')->primer_dato("%m-%Y");

        return array(
				'titulo' => 'Mensual',
                'datos' => $datos,
				'fecha' => $fecha,
				'adicional' => 'minViewMode: 1,',
				'formato' => 'mm-yyyy',
				'inicio' => reset($desde)['inicio'],
            );    
			
	}

    /**
     * @Route("/camion")
     * @Template()
     */
    public function camionAction(Request $request)
    {
		 if ($request->getMethod() == 'POST' and $request->request->get('fecha') != "" ) {
		 $fecha = $request->request->get('fecha');
		
		  }
		 else
			$fecha = date("d-m-Y");
		  
        $em = $this->getDoctrine()->getManager();
		
		$where = "where datos.id_tag_camiones not in (SELECT id_tag_camiones  FROM datos WHERE date(inicio) = :fecha GROUP BY id_tag_camiones,grua )" ;

		$camiones = $em->getRepository('Eye3ControlBundle:Datos')->camiones($where, date("Y-m-d", strtotime($fecha)));
        $datos = $em->getRepository('Eye3ControlBundle:Datos')->camion_dia(date("Y-m-d", strtotime($fecha)));
		$desde = $em->getRepository('Eye3ControlBundle:Datos')->primer_dato();

        return array(
                'datos' => $datos,
                'camiones' => $camiones,
				'fecha' => $fecha,
				'inicio' => reset($desde)['inicio'],
            );    
		
	}
	
    /**
     * @Route("/camion/semanal")
     * @Template()
     */
    public function camionSemanaAction(Request $request)
    {
		 if ($request->getMethod() == 'POST' and $request->request->get('fecha') != "" ) {
		 $fecha = $request->request->get('fecha');
		  }
		 else
			$fecha = date("d-m-Y");
		
        $em = $this->getDoctrine()->getManager();

		$where = "where datos.id_tag_camiones not in (SELECT id_tag_camiones  FROM datos WHERE DATE_FORMAT(inicio,'%u-%Y') = DATE_FORMAT( :fecha ,'%u-%Y' ) GROUP BY id_tag_camiones,grua )" ;

		$camiones = $em->getRepository('Eye3ControlBundle:Datos')->camiones($where, date("Y-m-d", strtotime($fecha)));
        $datos = $em->getRepository('Eye3ControlBundle:Datos')->camion_semana(date("Y-m-d", strtotime($fecha)));
		$desde = $em->getRepository('Eye3ControlBundle:Datos')->primer_dato();

        return array(
                'datos' => $datos,
                'camiones' => $camiones,
				'fecha' => $fecha,
				'inicio' => reset($desde)['inicio'],
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
		 else
			$fecha = date("m-Y");
		
        $em = $this->getDoctrine()->getManager();

		$where = "where datos.id_tag_camiones not in (SELECT id_tag_camiones FROM datos WHERE DATE_FORMAT(inicio,'%m-%Y') = DATE_FORMAT( :fecha ,'%m-%Y' ) GROUP BY id_tag_camiones,grua )" ;
		
		$camiones = $em->getRepository('Eye3ControlBundle:Datos')->camiones($where, date("Y-m-d", strtotime("01-".$fecha)));
        $datos = $em->getRepository('Eye3ControlBundle:Datos')->camion_mes(date("Y-m-d", strtotime("01-".$fecha)));
		$desde = $em->getRepository('Eye3ControlBundle:Datos')->primer_dato("%m-%Y");

        return array(
                'datos' => $datos,
                'camiones' => $camiones,
				'fecha' => $fecha,
				'inicio' => reset($desde)['inicio'],
				'primer' => date_create('01-'.$fecha)->modify('next Monday'),
            );    
	}
	

    /**
     * @Route("/grua")
     * @Template()
     */
    public function gruaAction(Request $request)
    {
		 if ($request->getMethod() == 'POST' and $request->request->get('fecha') != "" ) {
		 $fecha = $request->request->get('fecha');
		  }
		 else
			$fecha = date("d-m-Y");
		  
        $em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->grua_dia($fecha);
		$desde = $em->getRepository('Eye3ControlBundle:Datos')->primer_dato();

        return array(
                'datos' => $datos,
				'fecha' => $fecha,
				'inicio' => reset($desde)['inicio'],
            ); 
     }

}

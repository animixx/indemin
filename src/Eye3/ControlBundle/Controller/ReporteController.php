<?php

namespace Eye3\ControlBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class RegistroController
 *
 * @Route("/reportes")
 *
 * @package Eye3\ControlBundle\Controller
 */
class ReporteController extends Controller
{

    /**
     * 
     * @Route("/", name="reporte_semanal")
     * @Template("Eye3ControlBundle:Reporte:index.html.twig")
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function semanalAction(Request $request)
    {
       $dataTable = $this->get('data_tables.manager')->getTable('registroTable');
        if ($response = $dataTable->ProcessRequest($request)) {
            return $response;
        }

        return array(
            'dataTable' => $dataTable,
        );
    }

    /**
     * 
     * @Route("/", name="reporte_mensual")
     * @Template("Eye3ControlBundle:Reporte:index.html.twig")
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function mensualAction(Request $request)
    {
       $dataTable = $this->get('data_tables.manager')->getTable('registroTable');
        if ($response = $dataTable->ProcessRequest($request)) {
            return $response;
        }

        return array(
            'dataTable' => $dataTable,
        );
    }

}

<?php

namespace Eye3\ControlBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eye3\ControlBundle\Entity\Datos;

/**
 * Class RegistroController
 *
 * @Route("/historial")
 *
 * @package Eye3\ControlBundle\Controller
 */
class RegistroController extends Controller
{

    /**
     * Lists all Datos entities.
     *
     * @Route("/", name="registro_historial")
     * @Template()
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function indexAction(Request $request)
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
     * @Route("/descargas", name="descargas")
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function descargasAction(Request $request)
    {
	
			$em = $this->getDoctrine()->getManager();
            $viejito = $em->getRepository('Eye3ControlBundle:Datos')->primer_dato();

			return $this->render('Eye3ControlBundle:Registro:descargas.html.twig',
													array(
														'desde'         => $viejito[0]['inicio'],
													));
	
    }
}

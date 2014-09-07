<?php

namespace Eye3\ControlBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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

}

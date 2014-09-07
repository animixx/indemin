<?php

namespace Eye3\ControlBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


/**
 * Uso controller.
 *
 * @Route("/")
 */
class UsoController extends Controller
{

    /**
     * Lists all Registro entities.
     *
     * @Route("/uso", name="uso_sistema")
     * @Template()
	 * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $dataTable = $this->get('data_tables.manager')->getTable('accesoTable');
        if ($response = $dataTable->ProcessRequest($request)) {
            return $response;
        }

        return array(
            'dataTable' => $dataTable,
        );
    }

   
    /**
     * Lists all Users.
     *
     * @Route("/usuarios", name="usuarios")
     * @Template("Eye3ControlBundle:Uso:user.html.twig")
	 * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function userAction(Request $request)
    {
        $dataTable = $this->get('data_tables.manager')->getTable('usuariosTable');
        if ($response = $dataTable->ProcessRequest($request)) {
            return $response;
        }

        return array(
            'dataTable' => $dataTable,
        );
    }

   
}

<?php

namespace Eye3\ControlBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eye3\ControlBundle\Entity\Datos;

/**
 * Registro controller.
 *
 * @Route("/historial")
 */
class RegistroController extends Controller
{

    /**
     * Lists all Datos entities.
     *
     * @Route("/", name="registro_historial")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('Eye3ControlBundle:Datos')->findMax();

        return array(
            'entities' => $entities,
        );
    }

}

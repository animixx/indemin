<?php

namespace Eye3\ControlBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eye3\ControlBundle\Entity\Registro;

/**
 * Uso controller.
 *
 * @Route("/uso")
 */
class UsoController extends Controller
{

    /**
     * Lists all Registro entities.
     *
     * @Route("/", name="uso_sistema")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('Eye3ControlBundle:Registro')->findAll();

        return array(
            'entities' => $entities,
        );
    }

   
}

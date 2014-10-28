<?php

namespace Eye3\ControlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Eye3\ControlBundle\Entity\Registro;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
		
		// last username entered by the user
		$lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            ); 
			 $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Eye3ControlBundle:Usuario')->find($id);
			
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
			$em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Eye3ControlBundle:Usuario')->findOneByUsername($lastUsername);
			if ($entity) {
				$registry = new Registro();
				$registry->setAccion("fallo_ingreso");
				$registry->setFecha();
				$registry->setUsuario($entity);
				$em->persist($registry);
				$em->flush();
				}
        } else {
            $error = '';
			
        }

        
        return $this->render(
            'Eye3ControlBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }
}
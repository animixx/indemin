<?php

namespace Eye3\ControlBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Eye3\ControlBundle\Entity\Usuario;


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

	
	/**
    * Displays a form to create a new Usuario entity.
    *
    * @Route("/usuarios/new", name="nuevo_usuario")
    * @Template()
    */
    public function newAction(Request $request)
    {
		$entity = new Usuario();
        $form = $this->createFormBuilder( $entity, array(
            'method' => 'POST',
        ))
			->add('username', 'text', array('label'=>'Usuario','required' => true))
			->add('nombre', 'text', array('label'=>'Nombre','required' => true))
			->add('email', 'email',array('required' => true))
			->add('genero','choice', array(
			'empty_value' => 'Elija Genero',
			   'required' => true,
                'choices' => array(
                    '0'=>'H',
                    '1'=>'M',
                    ),
			'multiple'  => false,
                ))
			->add('password', 'repeated', array(
                'required'=>true,
                'type'=>'password',
                'invalid_message' => 'Las claves deben coincidir.',
                'first_options'  => array('label' => 'Contraseña'),
                'second_options' => array('label' => 'Confirmar'),
                ))
            ->add('tipo','choice', array(
            'empty_value' => 'Elija permisos',
                'choices' => array(
                    'ROLE_ADMIN'=>'Admin',
                    'ROLE_USER'=>'Usuario',
                    ),
			'multiple'  => false,
                ))
			->add('submit', 'submit', array('label' => 'Create'))
            ->getForm();

		
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
			$entity->setLastLogin();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('usuarios'));
        }

        $entity = new Usuario();
		
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
   
}

<?php

namespace Eye3\ControlBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Eye3\ControlBundle\Entity\Usuario;
use Eye3\ControlBundle\Entity\Registro;


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
	* @Security("has_role('ROLE_ADMIN')")
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
	* @Security("has_role('ROLE_ADMIN')")
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
	* @Security("has_role('ROLE_ADMIN')")
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
			$registry = new Registro();
			$registry->setAccion("Creación");
			$registry->setFecha();
			$registry->setUsuario($entity);
            $em->persist($entity);
            $em->persist($registry);
            $em->flush();

            return $this->redirect($this->generateUrl('usuarios'));
        }

        $entity = new Usuario();
		
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
	
	/**
     * Deletes a Usuario entity.
     *
     * @Route("/usuarios/deleteconfirm/{id}", name="user_deleteconfirm")
     * @Method("GET")
	 * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function deleteconfirmAction(Request $request, $id)
    {
		$deleteForm = $this->createDeleteForm($id);
        $deleteForm->handleRequest($request);
        
		 return array(
            'entity'      => $id,
            'delete_form' => $deleteForm->createView(),
        );
		

    } 
	
	/**
     * Deletes a Usuario entity.
     *
     * @Route("/{id}", name="users_delete")
	 * @Security("has_role('ROLE_ADMIN')")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Eye3ControlBundle:Usuario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Usuario entity.');
            }

            $entity->setActivo(0);
			$registry = new Registro();
			$registry->setAccion("Eliminación");
			$registry->setFecha();
			$registry->setUsuario($entity);
            $em->persist($registry);
			$em->persist($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('usuarios'));
    }

    /**
     * Creates a form to delete a Usuario entity by id.
     * @param mixed $id The entity id
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('users_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Borrar'))
            ->getForm()
        ;
    }
}

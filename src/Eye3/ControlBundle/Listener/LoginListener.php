<?php
 
namespace Eye3\ControlBundle\Listener;
 
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use Eye3\ControlBundle\Entity\Registro;
 
/**
 * Custom login listener.
 */
class LoginListener
{
	/** @var \Symfony\Component\Security\Core\SecurityContext */
	private $securityContext;
	
	/** @var \Doctrine\ORM\EntityManager */
	private $em;
	
	/**
	 * Constructor
	 * 
	 * @param SecurityContext $securityContext
	 * @param EntityManager        $em
	 */
	public function __construct(SecurityContext $securityContext, EntityManager $em)
	{
		$this->securityContext = $securityContext;
		$this->em = $em;
	}
	
	/**
	 * Do the magic.
	 * 
	 * @param InteractiveLoginEvent $event
	 */
	public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
	{
		if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
			// user has just logged in
		}
		
		if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			// user has logged in using remember_me cookie
		}
		
		// do some other magic here
		$user = $event->getAuthenticationToken()->getUser();
	
		$registry = new Registro();
		$registry->setAccion("ingreso");
		$registry->setFecha();
		$registry->setUsuario($user);
		$user->setLastLogin();
		$this->em->persist($registry);
		$this->em->persist($user);
		$this->em->flush();
				
	}
}
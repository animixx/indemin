<?php

namespace Eye3\ControlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction()
    {
		return $this->redirect($this->generateUrl('eye3_control_estadisticas_tiempo'));
    }
}

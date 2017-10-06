<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class AdherentController
 * @package AppBundle\Controller
 * @Route("adherent")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdherentController extends Controller
{
    /**
     * @Route("/index")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Adherent:index.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/edit")
     */
    public function editAction()
    {
        return $this->render('AppBundle:Adherent:edit.html.twig', array(
            // ...
        ));
    }

}

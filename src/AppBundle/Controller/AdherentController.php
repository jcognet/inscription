<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

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
    public function indexAction(Request $request)
    {
        $listeAdherents = $this->get('knp_paginator')->paginate(
            $this->getDoctrine()->getRepository('AppBundle:User')->getQueryListeUser($this->getUser()->getSaisonCourante()),
            1,
            100
        );

        $formRecherche = $this->createFormRecherche();

        //TODO : afficher la dernière date du certificat médical
        return $this->render('AppBundle:Adherent:index.html.twig', array(
            'liste_adherents' => $listeAdherents,
            'form'=>$formRecherche->createView()
        ));
    }

    /**
     * @Route("/edit")
     */
    public function editAction()
    {
        return $this->render('AppBundle:Adherent:edit.html.twig', array(// ...
        ));
    }


    protected function createFormRecherche(){

        //TODO : filter sur enfants, types de cotisations
        return $this->createFormBuilder()
            ->add('text', TextType::class)
            ->getForm()
            ;

    }

}

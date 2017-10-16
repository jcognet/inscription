<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Inscription;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
    const FORM_RECHERCHE_TEXT = 'text';

    /**
     * @Route("/index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $data = $this->getQueryListeAdherent($request);


        return $this->render('AppBundle:Adherent:index.html.twig', array(
            'liste_adherents' => $data['liste_adherent'],
            'form'            => $data['form']->createView()
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


    /**
     * Crée le formulaire de recherche
     * @return \Symfony\Component\Form\Form
     */
    protected function createFormRecherche()
    {
        $listeTypeCours = array('-' => null);
        foreach (Inscription::LISTE_TYPE_COURS as $typeCours) {
            $listeTypeCours[ucfirst($typeCours)] = $typeCours;
        }
        $listeTypeAdhesion = array('-' => null);
        foreach (Inscription::LISTE_TYPE_ADHESION as $typeAdhesion) {
            $listeTypeAdhesion[ucfirst($typeAdhesion)] = $typeAdhesion;
        }
        return $this->createFormBuilder()
            ->add('text', TextType::class, array(
                'required' => false,
                'attr'     => array(
                    'placeholder' => 'Recherche sur prénom / nom'
                )
            ))
            ->add('typeCours', ChoiceType::class, array(
                'choices' => $listeTypeCours,
                'label'   => 'Type de cours'
            ))
            ->add('typeAdhesion', ChoiceType::class, array(
                'choices' => $listeTypeAdhesion,
                'label'   => "Type d'adhésion"
            ))
            ->getForm();
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getQueryListeAdherent(Request $request)
    {
        $formRecherche = $this->createFormRecherche()->handleRequest($request);
        $listeFiltres  = array();
        if ($formRecherche->isSubmitted() && $formRecherche->isValid()) {
            $listeFiltres = $formRecherche->getData();
        }

        $listeAdherents = $this->get('knp_paginator')->paginate(
            $this->getDoctrine()->getRepository('AppBundle:User')->getQueryListeUser($this->getUser()->getSaisonCourante(), $listeFiltres),
            1,
            100,
            array('defaultSortFieldName' => 'u.prenom', 'defaultSortDirection' => 'asc')
        );

        return array(
            'form'           => $formRecherche,
            'liste_adherent' => $listeAdherents,
        );
    }


    /**
     * @Route("/recherche",
     * options={"expose"=true}
     *     )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rechercheAction(Request $request)
    {
        $data = $this->getQueryListeAdherent($request);

        return $this->render('@App/Adherent/Block/liste_adherents.html.twig', array(
            'liste_adherents' => $data['liste_adherent'],
        ));
    }

}

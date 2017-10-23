<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Saison;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommunController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function menuHeaderAction(Request $request)
    {
        $listeSaisons = $this->getDoctrine()->getRepository('AppBundle:Saison')->listeAll();
        $urlCourant = $this->get('request_stack')->getMasterRequest()->getUri();
        // replace this example code with whatever you need
        return $this->render('::menu_header.html.twig', [
            'liste_saisons' => $listeSaisons,
            'url_courant'=>$urlCourant
        ]);
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function menuGaucheAction(Request $request){
        return $this->render('AppBundle:Adherent/Block:menu.html.twig', [
            'liste_elements' => $this->get('AppBundle\Service\Menu\MenuService')->createListeElements('adherent'),
            'current_route'=>$this->get('request_stack')->getMasterRequest()->attributes->get('_route')
        ]);
    }

    /**
     * @Route("/changesaison", name="commun_chance_saison")
     */
    public function changeSaisonAction(Request $request){

        // Changement du changement de saison courante puis redirection vers la page courante
        if ($this->getUser() && $request->request->has('ddlSelectSaison')) {
            $this->getUser()->setSaisonCourante($this->getDoctrine()->getManager()->getReference(Saison::class, $request->request->get('ddlSelectSaison')));
            $this->getDoctrine()->getManager()->persist($this->getUser());
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirect($request->request->get('hidRedirection'));
    }
}

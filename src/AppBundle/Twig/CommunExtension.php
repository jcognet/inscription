<?php

namespace AppBundle\Twig;


use AppBundle\Entity\Saison;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CommunExtension extends \Twig_Extension
{

    /**
     * Request stack
     * @var null|RequestStack
     */
    private $rs = null;

    /**
     * Url de dev'
     * @var array
     */
    private $urlDev = array();

    private $tokenStorage = null;


    /**
     * CommunExtension constructor.
     * @param RequestStack $rs
     * @param $urlDev
     * @param TokenStorage $tokenStorage
     */
    public function __construct(RequestStack $rs,  $urlDev, TokenStorage $tokenStorage)
    {
        $this->rs     = $rs;
        $this->urlDev = $urlDev;
        $this->tokenStorage = $tokenStorage;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('mode_dev', array($this, 'modeDev')),
            new \Twig_SimpleFunction('espace_est_actif', array($this, 'espaceEstActif')),
            new \Twig_SimpleFunction('page_est_actif', array($this, 'pageEstActif')),
            new \Twig_SimpleFunction('get_saison_courante', array($this, 'getSaisonCourante')),
            new \Twig_SimpleFunction('get_label_type_cours', array($this, 'getLabelTypeCours')),
            new \Twig_SimpleFunction('get_label_type_adhesion', array($this, 'getLabelTypeAdhesion')),
        );
    }

    /**
     * Retourne si le site est en mode dev
     * @return bool
     */
    public function modeDev()
    {
        return in_array(
            $this->rs->getCurrentRequest()->getHost(),
            $this->urlDev
        );
    }

    /**
     * Retourne si le menu est actif ou pas pour un espace donné (se base sur la route)
     * @param $espace
     * @return bool
     */
    public function espaceEstActif($espace)
    {
        $estMenuActif = false;
        // Recherche de la route courante
        $request = $this->rs->getMasterRequest();
        $route   = $request->get('_route');

        // Les routes commencent toujours par le nom du bundle (et donc de l'espace)
        if (0 === strpos($route, $espace) || 0 === strpos($route, 'app_'.$espace)) {
            $estMenuActif = true;
        }
        return $estMenuActif;
    }

    /**
     * Retourne si le menu est actif ou pas pour un espace donné (se base sur la route)
     * @param $routePropose
     * @return bool
     */
    public function pageEstActif($routePropose)
    {
        // Recherche de la route courante
        $request = $this->rs->getCurrentRequest();
        $route   = $request->get('_route');
        return $routePropose === $route;
    }

    /**
     * Retourne la saison courante
     * @return Saison
     */
    public function getSaisonCourante(){
        return $this->tokenStorage->getToken()->getUser()->getSaisonCourante();
    }

    /**
     * Affiche le label du type de cours
     * @param string $typeCours
     * @return string
     */
    public function getLabelTypeCours(string $typeCours){
        return ucfirst($typeCours);
    }

    /**
     * Affiche le label du type de cours
     * @param string $typeAdhesion
     * @return string
     */
    public function getLabelTypeAdhesion(string $typeAdhesion){
        return ucfirst($typeAdhesion);
    }
}
<?php
namespace AppBundle\Service\Menu;


class Element
{
    /**
     * @var string
     */
    protected $name = "";

    /**
     * @var string
     */
    protected $route = "";


    /**
     * @var array
     */
    protected $listeInvisible = array();


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Element
     */
    public function setName(string $name): Element
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     * @return Element
     */
    public function setRoute(string $route): Element
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return array
     */
    public function getListeInvisible(): array
    {
        return $this->listeInvisible;
    }

    /**
     * @param array $listeInvisible
     * @return Element
     */
    public function setListeInvisible(array $listeInvisible): Element
    {
        $this->listeInvisible = $listeInvisible;
        return $this;
    }



}
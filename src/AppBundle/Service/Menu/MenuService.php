<?php

namespace AppBundle\Service\Menu;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;

class MenuService
{
    /**
     * @var Element[]
     */
    protected $listeElements = array();

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * @var string
     */
    protected $kernelProjetDir;

    public function __construct(Filesystem $fs, string $kernelProjetDir)
    {
        $this->fs              = $fs;
        $this->kernelProjetDir = $kernelProjetDir;
        if (substr($this->kernelProjetDir, -1) != DIRECTORY_SEPARATOR)
            $this->kernelProjetDir .= DIRECTORY_SEPARATOR;
        $this->parser = new Yaml();
    }


    /**
     * Crée une liste d'éléments à partir d'un type
     * @param string $type
     * @return Element[]
     */
    public function createListeElements(string $type)
    {
        $path = $this->kernelProjetDir . 'app/config/Menu/' . strtolower(trim($type)) . '.yml';
        $yml  = $this->parser->parse(file_get_contents($path));
        foreach ($yml['menu'] as $item) {
            $element = new Element();
            $element->setName($item['name'])
                ->setListeRoles($item['roles'])
                ->setRoute($item['route']);
            if(array_key_exists('liste_invisible', $item))
                $element->setListeInvisible($item['liste_invisible']);
            $this->listeElements[] = $element;
        }
        return $this->listeElements;
    }
}
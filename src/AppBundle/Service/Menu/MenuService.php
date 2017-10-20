<?php

namespace AppBundle\Service\Menu;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Parser;

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

    public function __construct(Filesystem $fs, string $kernelProjetDir, Parser $parser)
    {
        $this->fs              = $fs;
        $this->kernelProjetDir = $kernelProjetDir;
        if (substr($this->kernelProjetDir, -1) != DIRECTORY_SEPARATOR)
            $this->kernelProjetDir .= DIRECTORY_SEPARATOR;
        $this->parser = $parser;
    }


    public function createListeElements(string $type)
    {
        $path = $this->kernelProjetDir . 'app/config/Menu/'.strtolower(trim($type)).'yml';
        $yml = $this->parser->parse(file_get_contents($path));

    }
}
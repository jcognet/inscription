<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{


    protected function configure()
    {
        $this
            ->setName('import:adherent')
            ->addArgument('annee', InputArgument::REQUIRED, "Année de l'import des membres")
            ->setDescription("Import des adhérents");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $annee = $input->getArgument('annee');
        $output->writeln("Commande d'import des adhérents ".$annee);
        $this->getContainer()->get('AppBundle\Import\Import'.$annee)->importeDonnees($annee);
    }


}
<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{


    protected function configure()
    {
        $this
            ->setName('import:adherent')
            ->addArgument('annee', InputArgument::REQUIRED, "Année de l'import des membres")
            ->addOption('updatedatabase','u' , InputOption::VALUE_NONE, 'met à jour la base de donnée')
            ->setDescription("Import des adhérents");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $annee = $input->getArgument('annee');
        $output->writeln("Commande d'import des adhérents ".$annee);
        $output->writeln("Enregistrement en base : ".$input->getOption('updatedatabase'));
        $this->getContainer()->get('AppBundle\Import\Import'.$annee)->importeDonnees($annee, $input->getOption('updatedatabase'));
    }


}
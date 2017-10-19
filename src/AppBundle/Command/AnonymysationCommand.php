<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class AnonymysationCommand extends ContainerAwareCommand
{


    protected function configure()
    {
        $this
            ->setName('import:anonym')
            ->addOption('exception', 'exp', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, "Email d'exception pour l'anonymation des données", array())
            ->setDescription("Anonymmise les adhérents");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Protection sur l'environnement
        if ($this->getContainer()->getParameter('kernel.environment') != 'dev') {
            throw new \RuntimeException('Cette commande doit être seulement exécutée en environnement de dev.');
        }

        $now = new \DateTime();
        $output->writeln("Lancement de la commande d'anonymation des données à " . $now->format('d/m/Y à H:i:s'));

        // Récupération des exceptions
        $listeExceptionEmail = $input->getOption('exception');
        $helper         = $this->getHelper('question');
        $output->writeln("La liste d'exceptions est : " . implode(', ', $listeExceptionEmail));

        // COnfirmation pour continuer
        $question = new ConfirmationQuestion("Etes-vous sûr d'anonynimser les données (y|o|yes|oui)? ", false, '/^(y|o|yes|oui)/i');
        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $listeUser = array();
        $listeUserId = array();
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        foreach($listeExceptionEmail as $exceptionEmail){
            $user = $em->getRepository(User::class)->findOneByEmail($exceptionEmail);
            if(false === is_null($user)){
                $listeUser[] = $user;
                $listeUserId[] = $user->getId();
            }
        }
        $output->writeln("La liste des id exception est : " . implode(', ', $listeUserId));
        // Première protection
        if(count($listeUser) != count($listeExceptionEmail) ){
            $question = new ConfirmationQuestion("Il semble y avoir un nombre différent de id en base par rapport aux emails. Continuez ?  (y|o|yes|oui)? ", false, '/^(y|o|yes|oui)/i');
            if (!$helper->ask($input, $output, $question)) {
                return;
            }
        }
        $this->getContainer()->get('AppBundle\Import\Anonymisation')->anonymise($listeUser);
    }

}
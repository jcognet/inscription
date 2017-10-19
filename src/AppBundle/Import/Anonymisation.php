<?php

namespace AppBundle\Import;

use AppBundle\Entity\Adresse;
use AppBundle\Entity\PersonneContact;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Faker\Generator;
use Psr\Log\LoggerInterface;

class Anonymisation
{
    /**
     * @var LoggerInterface
     */
    protected $logger;


    /**
     * @var EntityManager
     */
    protected $em;

    protected $faker;

    public function __construct(LoggerInterface $logger, EntityManager $em, Generator $faker)
    {
        $this->logger = $logger;
        $this->em     = $em;
        $this->faker  = $faker;
    }

    /**
     * Anonymise la base
     * @param array $listException
     */
    public function anonymise(array $listException)
    {
        $this->anonymiseUser($listException);
        $this->anonymiseAdresse($listException);
        $this->anonymisePersonneContacte($listException);

        $this->em->flush();
    }

    /**
     * Anonyme les utilisateurs
     * @param array $listException
     */
    protected function anonymiseUser(array $listException)
    {
        $listUser = $this->em->getRepository(User::class)->findAll();
        foreach ($listUser as $u) {
            $isException = false;
            foreach ($listException as $e) {
                if ($u->getId() === $e->getId()) {
                    $isException = true;
                    break;
                }
            }
            if (false === $isException) {
                if ($u->getSexe() === User::SEXE_FEMININ)
                    $u->setPrenom($this->faker->firstNameFemale);
                elseif ($u->getSexe() === User::SEXE_MASCULIN)
                    $u->setPrenom($this->faker->firstNameMale);
                else
                    $u->setPrenom('');
                $u->setNom($this->faker->lastName);
                $u->setTelephone($this->faker->phoneNumber);
                $u->setDateNaissance($this->faker->dateTime);
                $u->setEmail(strtolower($u->getPrenom() . '.' . $u->getNom() . '@fun-effect.com'));
                $u->setNumeroFederal(uniqid());

                $this->logger->debug("Anonymisation de l'utilisateur : " . $u->getId());
                $this->em->persist($u);
            }
        }
    }

    /**
     * Anonyme les adresses.
     * @param array $listException
     */
    protected function anonymiseAdresse(array $listException)
    {
        $listAdresse = $this->em->getRepository(Adresse::class)->findAll();
        foreach ($listAdresse as $a) {
            $isException = false;
            foreach ($listException as $e) {
                if ($a->getId() === $e->getAdresse()->getId()) {
                    $isException = true;
                    break;
                }
            }
            if (false === $isException) {
                $a->setRue($this->faker->streetAddress);
                $a->setVille($this->faker->city);

                $this->logger->debug("Anonymisation de l'adresse : " . $a->getId());
                $this->em->persist($a);
            }
        }
    }

    /**
     * Anonyme les utilisateurs
     * @param array $listException
     */
    protected function anonymisePersonneContacte(array $listException)
    {
        $listPersonnContact= $this->em->getRepository(PersonneContact::class)->findAll();
        foreach ($listPersonnContact as $u) {
            $isException = false;
            foreach ($listException as $e) {
                if (false === is_null($u->getInscription()) && $u->getInscription()->getUser()->getId() === $e->getId()) {
                    $isException = true;
                    break;
                }
            }
            if (false === $isException) {
                    $u->setPrenom($this->faker->firstNameFemale);
                $u->setNom($this->faker->lastName);
                $u->setTelephone($this->faker->phoneNumber);
                $u->setEmail(strtolower($u->getPrenom() . '.' . $u->getNom() . '@fun-effect.com'));

                $this->logger->debug("Anonymisation de la personne à contacter  : " . $u->getId());
                $this->em->persist($u);
            }
        }
    }
}
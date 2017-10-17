<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Adresse;
use AppBundle\Entity\Club;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClubFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $adresse = new Adresse();
        $adresse->setRue('Gymanse Evariste Gallois - 5 rue des écoles')
            ->setVille('Nanterre')
            ->setCodePostal(92000);

        $club = new Club();
        $club->setNom('ESN Nanterre')
            ->setAdresse($adresse)
            ->setSiteInternet('http://www.aikidonanterre.com/')
            ->setNumeroFederal('11492004');

        $manager->persist($adresse);
        $manager->persist($club);
        $manager->flush();
    }
}
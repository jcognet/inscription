<?php
namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Saison;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SaisonFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $saison2015 = new Saison();
        $saison2015->setAnnee(2015)
            ->setCouleurFond('red')
            ->setCouleurPolice('white')
            ->setPrixCotisationAdulte(210)
            ->setPrixCotisationAdolescent(190)
            ->setPrixCotisationEnfant(160)
            ->setPartFederationEnfant(25)
            ->setPartFederationAdulte(35)
            ->setPartFederationDirigeant(55);

        $saison2016 = new Saison();
        $saison2016->setAnnee(2016)
            ->setCouleurFond('green')
            ->setCouleurPolice('white')
            ->setPrixCotisationAdulte(215)
            ->setPrixCotisationAdolescent(190)
            ->setPrixCotisationEnfant(160)
            ->setPartFederationEnfant(25)
            ->setPartFederationAdulte(35)
            ->setPartFederationDirigeant(55);


        $saison2017 = new Saison();
        $saison2017->setAnnee(2017)
            ->setCouleurFond('orange')
            ->setCouleurPolice('white')
            ->setPrixCotisationAdulte(215)
            ->setPrixCotisationAdolescent(190)
            ->setPrixCotisationEnfant(160)
            ->setPartFederationEnfant(25)
            ->setPartFederationAdulte(35)
            ->setPartFederationDirigeant(55);

        $this->addReference('saison_courante', $saison2017);

        $manager->persist($saison2015);
        $manager->persist($saison2016);
        $manager->persist($saison2017);
        $manager->flush();
    }
}
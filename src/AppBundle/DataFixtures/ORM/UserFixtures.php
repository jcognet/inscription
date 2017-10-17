<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Club;
use AppBundle\Entity\Saison;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;

class UserFixtures  extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setNom('Cognet')
            ->setPrenom('Jérôme')
            ->setEmail('jcognet@gmail.com')
            ->setDateNaissance(\DateTime::createFromFormat('d/m/Y', '03/01/1981'))
            ->setNumeroFederal('')
            ->setSexe('')
            ->setEnabled(true)
        ->setSaisonCourante($this->getReference('saison_courante'))
        ;

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, $user->getEmail());
        $user->setPassword($password);


        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            SaisonFixtures::class,
        );
    }

}
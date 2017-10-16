<?php

namespace AppBundle\Import;


use AppBundle\Entity\Adresse;
use AppBundle\Entity\CertificatMedical;
use AppBundle\Entity\Inscription;
use AppBundle\Entity\PersonneContact;
use AppBundle\Entity\Saison;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractImport
{
    const PROP_PRENOM              = "prenom";
    const PROP_NOM                 = "nom";
    const PROP_TELEPHONE           = "telephone";
    const PROP_EMAIL               = "email";
    const PROP_ADRESSE             = "adresse";
    const PROP_CP                  = "cp";
    const PROP_VILLE               = "ville";
    const PROP_NUMERO_LICENCE      = "licence";
    const PROP_COTISATION          = "cotisation";
    const PROP_PAIEMENT            = "paiement";
    const PROP_PRENOM_URGENCE      = "prenom_urgence";
    const PROP_NOM_URGENCE         = "nom_urgence";
    const PROP_TEL_URGENCE         = "tel_urgence";
    const PROP_DATE_NAISSANCE      = "date_naissance";
    const PROP_TYPE_COURS          = 'type_cours';
    const PROP_SEXE                = "sexe";
    const PROP_TYPE_LICENCE        = "type_licence";
    const PROP_CERTIFICAT_MEDEICAL = "certificat_medical";


    /**
     * Retourne la conversion entre le CSV et les propriétés. $propriété => $numeroColonne
     * @return array
     */
    abstract protected function listeConversionColonne();

    /**
     * Convertit le sexe lu du fichier CSV
     * @return mixed
     */
    abstract protected function convertitSexe($sexe);

    /**
     * Convertit le type de cours
     * @return mixed
     */
    abstract protected function convertitTypeCours($type);

    /**
     * Convertit le type de licence
     * @return mixed
     */
    abstract protected function convertitTypeLicence($type);

    /**
     * Liste des conversions de colonne
     * @var array
     */
    protected $listeConversionColonne = array();

    /**
     * @var EntityManager
     */
    protected $em;


    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var string
     */
    protected $repertoireDonnees;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Saison
     */
    protected $saison;

    public function __construct(EntityManager $em, Filesystem $fs, string $repertoireDonnees, Serializer $serializer, LoggerInterface $logger)
    {
        $this->em                = $em;
        $this->fs                = $fs;
        $this->repertoireDonnees = $repertoireDonnees;
        if (substr($this->repertoireDonnees, -1) != DIRECTORY_SEPARATOR)
            $this->repertoireDonnees .= DIRECTORY_SEPARATOR;
        $this->serializer = $serializer;
        $this->logger     = $logger;
    }

    /**
     * Importer les données
     * @param int $annee
     * @param bool $updateDatabase
     */
    public function importeDonnees(int $annee, $updateDatabase = true)
    {
        $this->checkFile($annee);
        $this->prepareImport($annee);
        $donnees                      = $this->litFichier($annee);
        $this->listeConversionColonne = $this->listeConversionColonne();
        $donneesConvertie             = $this->convertitDonnees($donnees);
        $listeEntite                  = $this->convertitEnEntites($donneesConvertie);
        dump($updateDatabase);
        if ($updateDatabase) {
            $this->logger->debug('Mise à jour de la BDD');
            $this->em->flush();
        }
        $this->checkImport($donneesConvertie, $updateDatabase);
    }

    /**
     * Vérifie l'existence d'un fichier
     * @param int $annee
     * @throws \Exception
     */
    protected function checkFile(int $annee)
    {
        if (false === $this->fs->exists($this->getFilePath($annee))) {
            throw new \Exception("Fichier inconnu : " . $this->getFilePath($annee));
        }
    }

    /**
     * Prépare l'import
     * @param int $annee
     */
    protected function prepareImport(int $annee)
    {
        $this->saison = $this->em->getRepository(Saison::class)->findOneBy(array('annee' => $annee));
    }

    /**
     * Retourne le nom du fichier CSV à importer
     * @param int $annee
     * @return string
     */
    protected function getFilePath(int $annee)
    {
        return $this->repertoireDonnees . $annee . '.csv';
    }

    /**
     * Lit le fichier CSV d'import
     * @param int $annee
     * @return mixed
     */
    protected function litFichier(int $annee)
    {
        return $this->serializer->decode(file_get_contents($this->getFilePath($annee)), 'csv');
    }

    /**
     * Retourne un tableau position colonne => propriété
     * @return array|null
     */
    protected function listeConversionColonneInverse()
    {
        return array_flip($this->listeConversionColonne);
    }

    /**
     * Prépare les données lues dans le fichier CSV
     * @param array $donnees
     * @return array
     */
    protected function convertitDonnees(array $donnees)
    {
        $outDonneesClean = array();
        $iPos            = 0;
        $listColonne     = $this->listeConversionColonneInverse();
        foreach ($donnees as $adherent) {
            $this->logger->debug("Conversion de la ligne " . $iPos . " avec les données : " . implode(', ', $adherent));
            $iCol             = 0;
            $donneesConvertie = array();
            foreach ($adherent as $col => $donnee) {
                if (array_key_exists($iCol, $listColonne)) {
                    $donneesConvertie[$listColonne[$iCol]] = $donnee;
                }
                $iCol++;
            }
            $this->logger->debug("Fin de la conversion de la ligne " . $iPos . " qui a donné : " . implode(', ', array_keys($donneesConvertie)) . "et valeur : " . implode(', ', $donneesConvertie));
            $outDonneesClean[] = $donneesConvertie;
            $iPos++;
        }
        return $outDonneesClean;
    }

    /**
     * Convertit une ligne du fichier CSV en entité
     * @param $donnees
     * @return array
     */
    protected function convertitEnEntites($donnees)
    {
        $listeAdherentConvertit = array();
        foreach ($donnees as $adherent) {
            // La date de naissnce est unique \o/
            $dateNaissance = \DateTime::createFromFormat('j/m/Y', $adherent[self::PROP_DATE_NAISSANCE])->setTime(0, 0, 0);
            $user          = $this->em->getRepository(User::class)->findOneByEmail($adherent[self::PROP_EMAIL]);
            if (0 === strlen($adherent[self::PROP_EMAIL])) {
                continue;
            }
            if (true === is_null($user)) {
                $user = new User();
                $user->setSaisonCourante($this->saison);
            }
            //  Création de l'adresse si elle n'existe pas
            if (true === is_null($user->getAdresse())) {
                $adresse = new Adresse();
                $user->setAdresse($adresse);
            }
            // Gestion de l'inscription
            if (true === is_null($user->getInscriptionDeSaison($this->saison))) {
                $inscription = new Inscription();
                $inscription->setUser($user)
                    ->setSaison($this->saison);
                $user->addInscription($inscription);
            }
            // Gestion de la personne ugence

            if (true === is_null($user->getInscriptionDeSaison($this->saison)->getPersonneContact())) {
                $personne = new PersonneContact();
                $personne->setInscription($user->getInscriptionDeSaison($this->saison));
                $user->getInscriptionDeSaison($this->saison)->setPersonneContact($personne);
            }

            // Aller, on mappe maintenant
            foreach ($adherent as $prop => $valeur) {
                switch ($prop) {
                    case self::PROP_PRENOM:
                        $user->setPrenom($valeur);
                        break;
                    case self::PROP_NOM:
                        $user->setNom($valeur);
                        break;
                    case self::PROP_EMAIL:
                        $user->setEmail($valeur);
                        break;
                    case self::PROP_TELEPHONE:
                        $user->setTelephone($valeur);
                        break;
                    case self::PROP_ADRESSE:
                        $user->getAdresse()->setRue($valeur);
                        break;
                    case self::PROP_CP:
                        $user->getAdresse()->setCodePostal($valeur);
                        break;
                    case self::PROP_VILLE:
                        $user->getAdresse()->setVille($valeur);
                        break;
                    case self::PROP_NUMERO_LICENCE:
                        $user->setNumeroFederal($valeur);
                        break;
                    case self::PROP_PRENOM_URGENCE:
                        $user->getInscriptionDeSaison($this->saison)->getPersonneContact()->setPrenom($valeur);
                        break;
                    case self::PROP_NOM_URGENCE:
                        $user->getInscriptionDeSaison($this->saison)->getPersonneContact()->setNom($valeur);
                        break;
                    case self::PROP_TEL_URGENCE:
                        $user->getInscriptionDeSaison($this->saison)->getPersonneContact()->setTelephone($valeur);
                        break;
                    case self::PROP_DATE_NAISSANCE:
                        $user->setDateNaissance($dateNaissance);
                        break;
                    case self::PROP_SEXE:
                        $user->setSexe($this->convertitSexe($valeur));
                        break;
                    case self::PROP_TYPE_LICENCE:
                        $user->getInscriptionDeSaison($this->saison)->setTypeAdhesion($this->convertitTypeLicence($valeur));
                        break;
                    case self::PROP_TYPE_COURS:
                        $user->getInscriptionDeSaison($this->saison)->setTypeCours($this->convertitTypeCours($valeur));
                        break;
                    case self::PROP_CERTIFICAT_MEDEICAL:
                        if ($valeur == $this->saison->getAnnee()) {
                            $certif = $user->getInscriptionDeSaison($this->saison)->getCertificatMedical();
                            if (true === is_null($certif)) {
                                $certif = new CertificatMedical();
                                $certif->setInscription($user->getInscriptionDeSaison($this->saison));
                                $user->getInscriptionDeSaison($this->saison)->setCertificatMedical($certif);
                            }
                            $certif
                                ->setDateEmission(\DateTime::createFromFormat('j/m/Y', '01/09/' . $this->saison->getAnnee())->setTime(0, 0, 0))
                                ->setDateAjout(new \DateTime());
                            $this->em->persist($certif);
                        }
                        break;
                }
            }
            $listeAdherentConvertit[] = $user;
            $this->em->persist($user);
            $this->em->persist($user->getAdresse());
            $this->em->persist($user->getInscriptionDeSaison($this->saison)->getPersonneContact());
            // Ajout du certificat médical si nécessaire
            if (false === array_key_exists(self::PROP_CERTIFICAT_MEDEICAL, $adherent)) {
                $certif = new CertificatMedical();
                $certif->setInscription($user->getInscriptionDeSaison($this->saison))
                    ->setDateEmission(\DateTime::createFromFormat('j/m/Y', '01/09/' . $this->saison->getAnnee())->setTime(0, 0, 0))
                    ->setDateAjout(new \DateTime());
                $user->getInscriptionDeSaison($this->saison)->setCertificatMedical($certif);
                $this->em->persist($certif);
            }

            $this->em->persist($user->getInscriptionDeSaison($this->saison));
        }
        return $listeAdherentConvertit;
    }

    /**
     * Vérifie si l'import est ok
     * @param array $donnees
     * @param bool $updateDatabase
     */
    protected function checkImport(array $donnees, bool $updateDatabase)
    {
        foreach ($donnees as $adherent) {
            if (0 === strlen($adherent[self::PROP_EMAIL])) {
                $this->logger->error("Pas de email pour : " . implode(',', $adherent));
            } elseif ($updateDatabase) {
                $user = $this->em->getRepository(User::class)->findOneByEmail($adherent[self::PROP_EMAIL]);
                if (true === is_null($user)) {
                    $this->logger->error("Problème avec l'adhérent : " . $user);
                }
            }
        }
    }
}
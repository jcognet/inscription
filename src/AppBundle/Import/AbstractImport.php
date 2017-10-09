<?php

namespace AppBundle\Import;


use AppBundle\Entity\Adresse;
use AppBundle\Entity\Inscription;
use AppBundle\Entity\Saison;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractImport
{
    const PROP_PRENOM         = "prenom";
    const PROP_NOM            = "nom";
    const PROP_TELEPHONE      = "telephone";
    const PROP_EMAIL          = "email";
    const PROP_ADRESSE        = "adresse";
    const PROP_CP             = "cp";
    const PROP_VILLE          = "ville";
    const PROP_NUMERO_LICENCE = "licence";
    const PROP_COTISATION     = "cotisation";
    const PROP_PAIEMENT       = "paiement";
    const PROP_PRENOM_URGENCE = "prenom_urgence";
    const PROP_NOM_URGENCE    = "nom_urgence";
    const PROP_TEL_URGENCE    = "tel_urgence";
    const PROP_DATE_NAISSANCE = "date_naissance";
    const PROP_TYPE_COURS     = 'type_cours';
    const PROP_SEXE           = "sexe";
    const PROP_TYPE_LICENCE   = "licence";


    /**
     * Retourne la conversion entre le CSV et les propriétés. $propriété => $numeroColonne
     * @return array
     */
    abstract protected function listeConversionColonne();

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

    public function importeDonnees(int $annee)
    {
        $this->checkFile($annee);
        $donnees                      = $this->litFichier($annee);
        $this->listeConversionColonne = $this->listeConversionColonne();
        $donneesConvertie             = $this->convertitDonnees($donnees);
        $listeEntite                  = $this->convertitEnEntites($donneesConvertie);
        dump($listeEntite);
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
        $this->logger->debug("Nombre de lignes dans le fichier Excel : " . count($donnees));
        $iPos        = 0;
        $listColonne = $this->listeConversionColonneInverse();
        foreach ($donnees as $adherent) {
            $this->logger->debug("Analyse de la ligne : " . $iPos);
            $iCol             = 0;
            $donneesConvertie = array();
            foreach ($adherent as $col => $donnee) {
                if (array_key_exists($iCol, $listColonne)) {
                    $donneesConvertie[$listColonne[$iCol]] = $donnee;
                }
                $iCol++;
            }
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
        $saison                 = new Saison(); //TODO : la récupérer en base si elle existe, la tranformer en propriété du service
        foreach ($donnees as $adherent) {
            $user = new User();// TODO : récupérer un utilisateur à partir de son prénom, nom
            //  Création de l'adresse si elle n'existe pas
            if (true === is_null($user->getAdresse())) {
                $adresse = new Adresse();
                $user->setAdresse($adresse);
            }
            // Gestion de l'inscription
            if( true === is_null($user->getInscriptionDeSaison($saison))){
                $inscription = new Inscription();
                $inscription->setUser($user)
                    ->setSaison($saison);
                $user->addInscription($inscription);
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
                }
            }
            $listeAdherentConvertit[] = $user;
        }
        return $listeAdherentConvertit;
    }
}
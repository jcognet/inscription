<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Adresse
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Adresse")
     */
    private $adresse;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Inscription", mappedBy="user")
     * @ORM\OrderBy({"dateTraitementDossier" = "DESC"})
     */
    private $inscriptions;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_federal", type="string", length=255)
     */
    private $numeroFederal;

    /**
     * @var string
     *
     * @ORM\Column(name="demande_justificatif", type="boolean")
     */
    private $demandeJustificatif = false;


    /**
     * Retourne l'année du certificat médical
     * @return null|int
     */
    public function getAnneeDerniereCertificatMedical()
    {
        $annee = null;
        /**@var \AppBundle\Entity\Inscription $derniereInscription * */
        $derniereInscription = $this->getDernereInscription();
        if (false === is_null($derniereInscription)) {
            $annee = $derniereInscription->getDateTraitementDossier()->format('Y');
        }
        return $annee;
    }

    /**
     * Retourne la dernière inscription
     * @return Inscription|null
     */
    public function getDerniereInscription()
    {
        $derniereInscription = $this->inscriptions->first();
        if (false === is_null($derniereInscription)) {
            return $derniereInscription;
        }
        return null;
    }

    /**
     * Retourne la première inscription
     * @return mixed|null
     */
    public function getPremiereInscription()
    {
        $premiereInscription = $this->inscriptions->last();
        if (false === is_null($premiereInscription)) {
            return $premiereInscription;
        }
        return null;
    }

    /**
     * Retourne la date de première inscription
     * @return \DateTime
     */
    public function getAnneePremiereInscription()
    {
        $date = null;
        /**@var \AppBundle\Entity\Inscription $derniereInscription * */
        $premiereInscription = $this->getPremiereInscription();
        if (false === is_null($premiereInscription)) {
            $date = $premiereInscription->getDateTraitementDossier();
        }
        return $date;
    }


    /**
     * Retourne si l'utilisateur a besoin d'un nouveau certificat médical
     * @return bool
     */
    public function necessiteNouveauCertificatMedical()
    {
        $necessite = true;
        $annee     = $this->getAnneeDerniereCertificatMedical();
        if (false === is_null($annee)) {
            $now       = new \DateTime();
            $necessite = ($now->format('Y') - $annee) > CertificatMedical::DUREE_VALIDITE_ANNEE_CERTIFICAT_MEDICAL;
        }
        return $necessite;
    }

    /**
     * Retourne la personne à contacter
     * @return null|PersonneContact
     */
    public function getPersonneContacte()
    {
        $personne    = null;
        $inscription = $this->getDerniereInscription();
        if (false === is_null($inscription)) {
            $personne = $inscription->getPersonneContact();
        }
        return $personne;
    }


    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set numeroFederal
     *
     * @param string $numeroFederal
     *
     * @return User
     */
    public function setNumeroFederal($numeroFederal)
    {
        $this->numeroFederal = $numeroFederal;

        return $this;
    }

    /**
     * Get numeroFederal
     *
     * @return string
     */
    public function getNumeroFederal()
    {
        return $this->numeroFederal;
    }

    /**
     * Set demandeJustificatif
     *
     * @param boolean $demandeJustificatif
     *
     * @return User
     */
    public function setDemandeJustificatif($demandeJustificatif)
    {
        $this->demandeJustificatif = $demandeJustificatif;

        return $this;
    }

    /**
     * Get demandeJustificatif
     *
     * @return boolean
     */
    public function getDemandeJustificatif()
    {
        return $this->demandeJustificatif;
    }

    /**
     * Set adresse
     *
     * @param \AppBundle\Entity\Adresse $adresse
     *
     * @return User
     */
    public function setAdresse(\AppBundle\Entity\Adresse $adresse = null)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return \AppBundle\Entity\Adresse
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Add inscription
     *
     * @param \AppBundle\Entity\Inscription $inscription
     *
     * @return User
     */
    public function addInscription(\AppBundle\Entity\Inscription $inscription)
    {
        $this->inscriptions[] = $inscription;

        return $this;
    }

    /**
     * Remove inscription
     *
     * @param \AppBundle\Entity\Inscription $inscription
     */
    public function removeInscription(\AppBundle\Entity\Inscription $inscription)
    {
        $this->inscriptions->removeElement($inscription);
    }

    /**
     * Get inscriptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInscriptions()
    {
        return $this->inscriptions;
    }
}

<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Inscription
 *
 * @ORM\Table(name="inscription")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InscriptionRepository")
 */
class Inscription
{
    const TYPE_COURS_ADULTE = 'adulte';
    const TYPE_COURS_ENFANT = 'enfant';

    const LISTE_TYPE_COURS = array(self::TYPE_COURS_ADULTE, self::TYPE_COURS_ENFANT);

    const TYPE_ADEHSION_ADULTE    = 'adulte';
    const TYPE_ADEHSION_ENFANT    = 'enfant';
    const TYPE_ADEHSION_DIRIGEANT = 'dirigeant';
    const TYPE_ADEHSION_AILLEURS= 'ailleurs';

    const LISTE_TYPE_ADHESION = array(self::TYPE_ADEHSION_ADULTE, self::TYPE_ADEHSION_ENFANT, self::TYPE_ADEHSION_DIRIGEANT, self::TYPE_ADEHSION_AILLEURS  );

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateReceptionDossier", type="datetime", nullable=true)
     */
    private $dateReceptionDossier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTraitementDossier", type="datetime", nullable=true)
     */
    private $dateTraitementDossier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDepot", type="datetime", nullable=true)
     */
    private $dateDepot;

    /**
     * @var string
     *
     * @ORM\Column(name="typeCours", type="string", length=10)
     */
    private $typeCours;

    /**
     * @var string
     *
     * @ORM\Column(name="typeAdhesion", type="string", length=10)
     */
    private $typeAdhesion;

    /**
     * @var ArrayCollection
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="inscriptions")
     */
    private $user;

    /**
     * @var ArrayCollection
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Saison", inversedBy="inscriptions")
     */
    private $saison;

    /**
     * @var CertificatMedical
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\CertificatMedical", inversedBy="inscription")
     */
    private $certificatMedical;

    /**
     * @var PersonneContact
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\PersonneContact", inversedBy="inscription")
     */
    private $personneContact;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateReceptionDossier
     *
     * @param \DateTime $dateReceptionDossier
     *
     * @return Inscription
     */
    public function setDateReceptionDossier($dateReceptionDossier)
    {
        $this->dateReceptionDossier = $dateReceptionDossier;

        return $this;
    }

    /**
     * Get dateReceptionDossier
     *
     * @return \DateTime
     */
    public function getDateReceptionDossier()
    {
        return $this->dateReceptionDossier;
    }

    /**
     * Set dateTraitementDossier
     *
     * @param \DateTime $dateTraitementDossier
     *
     * @return Inscription
     */
    public function setDateTraitementDossier($dateTraitementDossier)
    {
        $this->dateTraitementDossier = $dateTraitementDossier;

        return $this;
    }

    /**
     * Get dateTraitementDossier
     *
     * @return \DateTime
     */
    public function getDateTraitementDossier()
    {
        return $this->dateTraitementDossier;
    }

    /**
     * Set dateDepot
     *
     * @param \DateTime $dateDepot
     *
     * @return Inscription
     */
    public function setDateDepot($dateDepot)
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    /**
     * Get dateDepot
     *
     * @return \DateTime
     */
    public function getDateDepot()
    {
        return $this->dateDepot;
    }

    /**
     * Set typeCours
     *
     * @param string $typeCours
     *
     * @return Inscription
     */
    public function setTypeCours($typeCours)
    {
        $this->typeCours = $typeCours;

        return $this;
    }

    /**
     * Get typeCours
     *
     * @return string
     */
    public function getTypeCours()
    {
        return $this->typeCours;
    }

    /**
     * Set typeAdhesion
     *
     * @param string $typeAdhesion
     *
     * @return Inscription
     */
    public function setTypeAdhesion($typeAdhesion)
    {
        $this->typeAdhesion = $typeAdhesion;

        return $this;
    }

    /**
     * Get typeAdhesion
     *
     * @return string
     */
    public function getTypeAdhesion()
    {
        return $this->typeAdhesion;
    }


    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Inscription
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set certificatMedical
     *
     * @param \AppBundle\Entity\CertificatMedical $certificatMedical
     *
     * @return Inscription
     */
    public function setCertificatMedical(\AppBundle\Entity\CertificatMedical $certificatMedical = null)
    {
        $this->certificatMedical = $certificatMedical;

        return $this;
    }

    /**
     * Get certificatMedical
     *
     * @return \AppBundle\Entity\CertificatMedical
     */
    public function getCertificatMedical()
    {
        return $this->certificatMedical;
    }

    /**
     * Set personneContact
     *
     * @param \AppBundle\Entity\PersonneContact $personneContact
     *
     * @return Inscription
     */
    public function setPersonneContact(\AppBundle\Entity\PersonneContact $personneContact = null)
    {
        $this->personneContact = $personneContact;

        return $this;
    }

    /**
     * Get personneContact
     *
     * @return \AppBundle\Entity\PersonneContact
     */
    public function getPersonneContact()
    {
        return $this->personneContact;
    }

    /**
     * Set saison
     *
     * @param \AppBundle\Entity\Saison $saison
     *
     * @return Inscription
     */
    public function setSaison(\AppBundle\Entity\Saison $saison = null)
    {
        $this->saison = $saison;

        return $this;
    }

    /**
     * Get saison
     *
     * @return \AppBundle\Entity\Saison
     */
    public function getSaison()
    {
        return $this->saison;
    }
}

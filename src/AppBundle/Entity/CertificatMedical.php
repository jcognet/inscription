<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CertificatMedical
 *
 * @ORM\Table(name="certificat_medical")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CertificatMedicalRepository")
 */
class CertificatMedical
{
    /**
     * Durée de validation du certif' médical
     */
    const DUREE_VALIDITE_ANNEE_CERTIFICAT_MEDICAL = 3;

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
     * @ORM\Column(name="dateEmission", type="datetime")
     */
    private $dateEmission;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAjout", type="datetime")
     */
    private $dateAjout;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Inscription", mappedBy="certificatMedical")
     */
    private $inscription;


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
     * Set dateEmission
     *
     * @param \DateTime $dateEmission
     *
     * @return CertificatMedical
     */
    public function setDateEmission($dateEmission)
    {
        $this->dateEmission = $dateEmission;

        return $this;
    }

    /**
     * Get dateEmission
     *
     * @return \DateTime
     */
    public function getDateEmission()
    {
        return $this->dateEmission;
    }

    /**
     * Set dateAjout
     *
     * @param \DateTime $dateAjout
     *
     * @return CertificatMedical
     */
    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    /**
     * Get dateAjout
     *
     * @return \DateTime
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    /**
     * Set inscription
     *
     * @param \AppBundle\Entity\Inscription $inscription
     *
     * @return CertificatMedical
     */
    public function setInscription(\AppBundle\Entity\Inscription $inscription = null)
    {
        $this->inscription = $inscription;

        return $this;
    }

    /**
     * Get inscription
     *
     * @return \AppBundle\Entity\Inscription
     */
    public function getInscription()
    {
        return $this->inscription;
    }
}

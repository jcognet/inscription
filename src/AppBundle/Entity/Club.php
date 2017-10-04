<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Club
 *
 * @ORM\Table(name="club")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClubRepository")
 */
class Club
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var Adresse
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Adresse")
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroFederal", type="string", length=255)
     */
    private $numeroFederal;

    /**
     * @var string
     *
     * @ORM\Column(name="siteInternet", type="string", length=255)
     */
    private $siteInternet;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Club
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
     * @return Club
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
     * Set siteInternet
     *
     * @param string $siteInternet
     *
     * @return Club
     */
    public function setSiteInternet($siteInternet)
    {
        $this->siteInternet = $siteInternet;

        return $this;
    }

    /**
     * Get siteInternet
     *
     * @return string
     */
    public function getSiteInternet()
    {
        return $this->siteInternet;
    }

    /**
     * Set adresse
     *
     * @param \AppBundle\Entity\Adresse $adresse
     *
     * @return Club
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
}

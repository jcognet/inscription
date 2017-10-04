<?php

namespace AppBundle\Entity;

use function Couchbase\defaultDecoder;
use Doctrine\ORM\Mapping as ORM;

/**
 * Saison
 *
 * @ORM\Table(name="saison")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SaisonRepository")
 */
class Saison
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
     * @var \DateTime
     *
     * @ORM\Column(name="annee", type="datetime")
     */
    private $annee;

    /**
     * @var string
     *
     * @ORM\Column(name="couleur", type="string", length=255)
     */
    private $couleur;

    /**
     * @var float
     *
     * @ORM\Column(name="prixCotisationAdulte", type="float")
     */
    private $prixCotisationAdulte;

    /**
     * @var float
     *
     * @ORM\Column(name="prixCotisationEnfant", type="float")
     */
    private $prixCotisationEnfant;

    /**
     * @var float
     *
     * @ORM\Column(name="partFederationEnfant", type="float")
     */
    private $partFederationEnfant;

    /**
     * @var float
     *
     * @ORM\Column(name="partFederationAdulte", type="float")
     */
    private $partFederationAdulte;

    /**
     * @var string
     *
     * @ORM\Column(name="partFederationDirigeant", type="string", length=255)
     */
    private $partFederationDirigeant;

    /**
     * @var float
     *
     * @ORM\Column(name="prixCotisationAdolescent", type="float")
     */
    private $prixCotisationAdolescent;


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
     * Set annee
     *
     * @param \DateTime $annee
     *
     * @return Saison
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return \DateTime
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     *
     * @return Saison
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get couleur
     *
     * @return string
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set prixCotisationAdulte
     *
     * @param float $prixCotisationAdulte
     *
     * @return Saison
     */
    public function setPrixCotisationAdulte($prixCotisationAdulte)
    {
        $this->prixCotisationAdulte = $prixCotisationAdulte;

        return $this;
    }

    /**
     * Get prixCotisationAdulte
     *
     * @return float
     */
    public function getPrixCotisationAdulte()
    {
        return $this->prixCotisationAdulte;
    }

    /**
     * Set prixCotisationEnfant
     *
     * @param float $prixCotisationEnfant
     *
     * @return Saison
     */
    public function setPrixCotisationEnfant($prixCotisationEnfant)
    {
        $this->prixCotisationEnfant = $prixCotisationEnfant;

        return $this;
    }

    /**
     * Get prixCotisationEnfant
     *
     * @return float
     */
    public function getPrixCotisationEnfant()
    {
        return $this->prixCotisationEnfant;
    }

    /**
     * Set partFederationEnfant
     *
     * @param float $partFederationEnfant
     *
     * @return Saison
     */
    public function setPartFederationEnfant($partFederationEnfant)
    {
        $this->partFederationEnfant = $partFederationEnfant;

        return $this;
    }

    /**
     * Get partFederationEnfant
     *
     * @return float
     */
    public function getPartFederationEnfant()
    {
        return $this->partFederationEnfant;
    }

    /**
     * Set partFederationAdulte
     *
     * @param float $partFederationAdulte
     *
     * @return Saison
     */
    public function setPartFederationAdulte($partFederationAdulte)
    {
        $this->partFederationAdulte = $partFederationAdulte;

        return $this;
    }

    /**
     * Get partFederationAdulte
     *
     * @return float
     */
    public function getPartFederationAdulte()
    {
        return $this->partFederationAdulte;
    }

    /**
     * Set partFederationDirigeant
     *
     * @param string $partFederationDirigeant
     *
     * @return Saison
     */
    public function setPartFederationDirigeant($partFederationDirigeant)
    {
        $this->partFederationDirigeant = $partFederationDirigeant;

        return $this;
    }

    /**
     * Get partFederationDirigeant
     *
     * @return string
     */
    public function getPartFederationDirigeant()
    {
        return $this->partFederationDirigeant;
    }

    /**
     * Set prixCotisationAdolescent
     *
     * @param float $prixCotisationAdolescent
     *
     * @return Saison
     */
    public function setPrixCotisationAdolescent($prixCotisationAdolescent)
    {
        $this->prixCotisationAdolescent = $prixCotisationAdolescent;

        return $this;
    }

    /**
     * Get prixCotisationAdolescent
     *
     * @return float
     */
    public function getPrixCotisationAdolescent()
    {
        return $this->prixCotisationAdolescent;
    }

    /**
     * Retourne la part de la fédération suivant le type d'inscription
     *
     * @param Inscription $inscription
     * @return float|null|string
     * @throws \Exception
     */
    public function getPartFeredation(Inscription $inscription){
        $cotisation = null;
        switch ($inscription->getTypeAdhesion()){
            case Inscription::TYPE_ADEHSION_ADULTE:
                $cotisation  = $this->getPartFederationAdulte();
                break;
            case Inscription::TYPE_ADEHSION_DIRIGEANT:
                $cotisation  = $this->getPartFederationDirigeant();
                break;
            case Inscription::TYPE_ADEHSION_ENFANT:
                $cotisation  = $this->getPartFederationEnfant();
                break;
            default:
                throw new \Exception("Type adhésion inconnu : ".$inscription->getTypeAdhesion());
        }
        return $cotisation;
    }

    public function getPrixCotisation(Inscription $inscription){
        $cotisation = null;
        $age = $inscription->getUser()->getDateNaissance();
        if(false === is_null($age)){
            if($age>=18){
                $cotisation = $this->getPrixCotisationAdulte() ;
            }elseif($age>=13){
                $cotisation = $this->getPrixCotisationAdolescent() ;
            }else{
                $cotisation = $this->getPrixCotisationEnfant() ;
            }
        }
        return $cotisation;
    }
}

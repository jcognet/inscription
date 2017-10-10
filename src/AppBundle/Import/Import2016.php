<?php

namespace AppBundle\Import;


class Import2016 extends AbstractImport
{
    /**
     * @inheritdoc
     */
    protected function convertitSexe($sexe)
    {
        return $sexe;
    }

    /**
     * @inheritdoc
     */
    protected function listeConversionColonne()
    {
        return array(
            self::PROP_NOM            => 0,
            self::PROP_PRENOM         => 1,
            self::PROP_TELEPHONE      => 2,
            self::PROP_EMAIL          => 3,
            self::PROP_ADRESSE        => 4,
            self::PROP_CP             => 5,
            self::PROP_VILLE          => 6,
            self::PROP_NUMERO_LICENCE => 7,
            self::PROP_NOM_URGENCE    => 10,
            self::PROP_PRENOM_URGENCE => 11,
            self::PROP_TEL_URGENCE    => 12,
            self::PROP_DATE_NAISSANCE => 13,
            self::PROP_SEXE           => 19,
            self::PROP_TYPE_LICENCE   => 20,
            self::PROP_TYPE_COURS     => 17
        );
    }

    /**
     * Convertit le type de cours
     * @return mixed
     */
    protected function convertitTypeCours($type)
    {
        return strtolower($type);
    }

    /**
     * Convertit le type de licence
     * @return mixed
     */
    protected function convertitTypeLicence($type)
    {
        return strtolower($type);
    }
}
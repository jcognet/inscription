<?php

namespace AppBundle\Import;


class Import2016 extends AbstractImport
{

    /**
     * @inheritdoc
     */
    protected function listeConversionColonne()
    {
        return array(
            self::PROP_NOM       => 0,
            self::PROP_PRENOM    => 1,
            self::PROP_TELEPHONE => 2,
            self::PROP_EMAIL     => 3,
            self::PROP_ADRESSE   => 4,
            self::PROP_CP        => 5,
            self::PROP_VILLE     => 6,
        );
    }
}
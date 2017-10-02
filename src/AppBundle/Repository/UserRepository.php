<?php

namespace UserBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * retourne le nombre d'utilisateurs actifs
     * @return array
     */
    public function getNombreUserActifs()
    {
        $nbUserArray = $this->createQueryBuilder('u')
            ->select('count(u.id) nb')
            ->where('u.enabled = :actif')
            ->setParameter('actif', true)
            ->getQuery()
            ->getScalarResult();
        return reset($nbUserArray)['nb'];
    }
}
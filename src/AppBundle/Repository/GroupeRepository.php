<?php

namespace AppBundle\Repository;

/**
 * GroupeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupeRepository extends \Doctrine\ORM\EntityRepository
{
    public function getListGroupes()
    {
        return $this
            ->createQueryBuilder('c');
    }
}

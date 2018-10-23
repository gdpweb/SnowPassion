<?php /** @noinspection PhpCSValidationInspection */
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */

/** @noinspection PhpCSValidationInspection */

namespace AppBundle\Repository;

/**
 * trickRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TrickRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAll()
    {
        $query = $this->createQueryBuilder('a')
            ->getQuery();
        return $query->getResult();
    }

    public function getListTricks($limit)
    {
        $query = $this->createQueryBuilder('a')
            ->setMaxResults($limit)
            ->getQuery();
        return $query->getResult();
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countTricksMax()
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
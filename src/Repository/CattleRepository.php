<?php

namespace App\Repository;

use App\Entity\Cattle;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cattle>
 *
 * @method Cattle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cattle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cattle[]    findAll()
 * @method Cattle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CattleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cattle::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Cattle $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Cattle $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getForSlaughter()
    {
        $now = new DateTime('now');
        $sub = $now->sub(new \DateInterval('P5Y'));
        $date = $sub->format('Y-m-d');

        return $this->createQueryBuilder('c')
            ->where('c.slaughter = :slaughter OR c.birth < :birth')
            ->setParameter('slaughter', true)
            ->setParameter('birth', $date)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getSlaughtered()
    {
        return $this->createQueryBuilder('c')
            ->where('c.slaughtered = :slaughtered')
            ->setParameter('slaughtered', true)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getMilk()
    {
        return $this->createQueryBuilder('c')
            ->select('sum(c.milk) as milk')
            ->where('c.slaughtered = :slaughtered')
            ->setParameter('slaughtered', false)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getRation()
    {
        return $this->createQueryBuilder('c')
            ->select('sum(c.ration) as ration')
            ->where('c.slaughtered = :slaughtered')
            ->setParameter('slaughtered', false)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}

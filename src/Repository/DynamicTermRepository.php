<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DynamicTerm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DynamicTerm|null find($id, $lockMode = null, $lockVersion = null)
 * @method DynamicTerm|null findOneBy(array $criteria, array $orderBy = null)
 * @method DynamicTerm[]    findAll()
 * @method DynamicTerm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DynamicTermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DynamicTerm::class);
    }

    public function findByPlaceholders(array $placeholder): array
    {
        if (empty($placeholder)) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('dt');

        return $queryBuilder
            ->andWhere($queryBuilder->expr()->in('dt.placeholder', $placeholder))
            ->getQuery()
            ->getResult()
        ;
    }
}

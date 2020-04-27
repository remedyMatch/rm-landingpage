<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Mention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Mention|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mention|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mention[]    findAll()
 * @method Mention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MentionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mention::class);
    }

    public function findAllOrderedWithLimit(bool $germanLast, ?int $limit = null): array
    {
        $queryBuilder = $this->createQueryBuilder('mention');
        if ($germanLast) {
            $queryBuilder->orderBy('mention.isGerman');
        }
        $queryBuilder
            ->addOrderBy('mention.priority', 'DESC')
            ->addOrderBy('mention.title');

        if (null !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder->getQuery()->getArrayResult();
    }
}

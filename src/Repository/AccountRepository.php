<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account|null findOneByEmail(array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function findUnreviewed(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.reviewed_at IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function findRejected(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.is_rejected = 1')
            ->getQuery()
            ->getResult();
    }
}

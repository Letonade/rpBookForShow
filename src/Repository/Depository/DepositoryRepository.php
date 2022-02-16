<?php

declare(strict_types=1);

namespace App\Repository\Depository;

use App\Document\Depository\Depository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class DepositoryRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depository::class);
    }

}

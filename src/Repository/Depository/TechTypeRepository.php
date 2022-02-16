<?php

declare(strict_types=1);

namespace App\Repository\Depository;

use App\Document\Depository\Properties\CapacityType\AmmunitionRpItem;
use App\Document\Depository\Properties\TechType\TechType;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class TechTypeRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TechType::class);
    }

}

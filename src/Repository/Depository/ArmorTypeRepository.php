<?php

declare(strict_types=1);

namespace App\Repository\Depository;

use App\Document\Depository\Properties\ArmorType\ArmorType;
use App\Document\Depository\Properties\CapacityType\AmmunitionRpItem;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class ArmorTypeRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArmorType::class);
    }

}

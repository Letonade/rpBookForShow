<?php

declare(strict_types=1);

namespace App\Repository\Depository;

use App\Document\Depository\Properties\CapacityType\AmmunitionRpItem;
use App\Document\Depository\Properties\CapacityType\CapacityType;
use App\Document\Depository\Properties\FamilyDescription\FamilyDescription;
use App\Document\Depository\Properties\Size\Size;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class SizeRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Size::class);
    }

}

<?php

declare(strict_types=1);

namespace App\Repository\Depository;

use App\Document\Depository\Properties\CapacityType\AmmunitionRpItem;
use App\Document\Depository\Properties\SpeedMovement\SpeedMovementType;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class SpeedMovementTypeRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpeedMovementType::class);
    }

    public function findCaseInsentive(string $field, string $search)
    {
        $result = $this->createQueryBuilder($this->documentName)
            ->field($field)
            ->equals($search)
            ->getQuery(['collation' => ['locale' => 'en', 'strength' => 2]])
            ->execute()
            ->toArray();
        if (!isset($result[0])){
            return null;
        }
        return $result[0];
    }

}

<?php

declare(strict_types=1);

namespace App\Repository\Depository;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\DamageType\DamageType;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;

class DamageTypeRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DamageType::class);
    }

    public function findDamageTypeByAcronym(string $acronym, Depository $depository): ?DamageType
    {
        $damageTypesInDb = $this->findBy([
            "depository" => $depository,
        ]);

        foreach ($damageTypesInDb as $damageTypeInDb) {
            /** @var DamageType $damageTypeInDb */
            if (strtoupper(preg_replace('/\s+/', '', $damageTypeInDb->getAcronym()))
                === strtoupper($acronym))
            {
                return $damageTypeInDb;
            }
        }
        return null;

    }

}

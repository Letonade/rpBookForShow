<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Depository\Properties\CapacityType\CapacityType;

trait RpItemWithAmmunitionType
{
    /**
     * @var CapacityType
     * @MongoDB\ReferenceOne(targetDocument=CapacityType::class, storeAs="id", name="ammunition_type")
     */
    protected $ammunitionType;

    public function getAmmunitionType(): CapacityType
    {
        return $this->ammunitionType;
    }

    public function setAmmunitionType(CapacityType $ammunitionType): void
    {
        $this->ammunitionType = $ammunitionType;
    }

}

<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

trait RpItemWithNumberOfWeaponSlot
{
    /**
     * @var int
     * @MongoDB\Field(type="int", name="number_of_weapon_slot")
     */
    protected $numberOfWeaponSlot = 0;

    public function getNumberOfWeaponSlot(): int
    {
        return $this->numberOfWeaponSlot;
    }

    public function setNumberOfWeaponSlot(int $numberOfWeaponSlot): void
    {
        $this->numberOfWeaponSlot = $numberOfWeaponSlot;
    }
}

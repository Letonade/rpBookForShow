<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

trait RpItemWithArmorClassModificator
{
    /**
     * @var int
     * @MongoDB\Field(type="int", name="energetic_armor_class_modificator")
     */
    protected $energeticArmorClassModificator = 0;

    /**
     * @var int
     * @MongoDB\Field(type="int", name="kinetic_armor_class_modificator")
     */
    protected $kineticArmorClassModificator = 0;

    public function getEnergeticArmorClassModificator(): int
    {
        return $this->energeticArmorClassModificator;
    }

    public function setEnergeticArmorClassModificator(int $energeticArmorClassModificator): void
    {
        $this->energeticArmorClassModificator = $energeticArmorClassModificator;
    }

    public function getKineticArmorClassModificator(): int
    {
        return $this->kineticArmorClassModificator;
    }

    public function setKineticArmorClassModificator(int $kineticArmorClassModificator): void
    {
        $this->kineticArmorClassModificator = $kineticArmorClassModificator;
    }
}

<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

trait RpItemWithNumberOfUpgradeSlot
{
    /**
     * @var int
     * @MongoDB\Field(type="int", name="number_of_upgrade_slot")
     */
    protected $numberOfUpgradeSlot = 0;

    public function getNumberOfUpgradeSlot(): int
    {
        return $this->numberOfUpgradeSlot;
    }

    public function setNumberOfUpgradeSlot(int $numberOfUpgradeSlot): void
    {
        $this->numberOfUpgradeSlot = $numberOfUpgradeSlot;
    }
}

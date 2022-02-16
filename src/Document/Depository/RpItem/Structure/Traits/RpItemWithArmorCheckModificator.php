<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

trait RpItemWithArmorCheckModificator
{
    /**
     * @var int
     * @MongoDB\Field(type="int", name="armor_check_modificator")
     */
    protected $armorCheckModificator = 0;

    public function getArmorCheckModificator(): int
    {
        return $this->armorCheckModificator;
    }

    public function setArmorCheckModificator(int $armorCheckModificator): void
    {
        $this->armorCheckModificator = $armorCheckModificator;
    }

}

<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Depository\Properties\WeaponCategory\WeaponCategory;

trait RpItemWithWeaponCategory
{
    /**
     * @var WeaponCategory|null
     * @MongoDB\ReferenceOne  (targetDocument=WeaponCategory::class, storeAs="id", name="weapon_category")
     */
    protected $weaponCategory;

    public function getWeaponCategory(): ?WeaponCategory
    {
        return $this->weaponCategory;
    }

    public function setWeaponCategory(?WeaponCategory $weaponCategory): void
    {
        $this->weaponCategory = $weaponCategory;
    }
}

<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Depository\Properties\WeaponCategory\WeaponCategory;

trait RpItemWithFixedStrength
{
    /**
     * @var int
     * @MongoDB\Field(type="int", name="fixed_strength")
     */
    protected $fixedStrength;

    public function getFixedStrength(): int
    {
        return $this->fixedStrength;
    }

    public function setFixedStrength(int $fixedStrength): void
    {
        $this->fixedStrength = $fixedStrength;
    }
}

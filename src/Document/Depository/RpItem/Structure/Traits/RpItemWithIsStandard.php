<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

trait RpItemWithIsStandard
{

    /**
     * @var bool
     * @MongoDB\Field(type="bool")
     */
    protected $isStandard;

    public function isStandard(): bool
    {
        return $this->isStandard;
    }

    public function setIsStandard(bool $isStandard): void
    {
        $this->isStandard = $isStandard;
    }

}

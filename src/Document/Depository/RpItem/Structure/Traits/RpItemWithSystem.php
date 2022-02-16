<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

trait RpItemWithSystem
{
    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $system;

    public function getSystem(): string
    {
        return $this->system;
    }

    public function setSystem(string $system): void
    {
        $this->system = $system;
    }
}

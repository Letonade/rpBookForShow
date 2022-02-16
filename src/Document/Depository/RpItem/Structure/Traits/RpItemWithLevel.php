<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

trait RpItemWithLevel
{
    /**
     * @var int
     * @MongoDB\Field(type="int")
     */
    protected $level;

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }
}

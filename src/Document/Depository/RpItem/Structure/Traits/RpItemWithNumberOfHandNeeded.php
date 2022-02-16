<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

trait RpItemWithNumberOfHandNeeded
{
    /**
     * @var int
     * @MongoDB\Field(type="int", name="number_of_hand_needed")
     */
    protected $numberOfHandNeeded = 1;

    public function getNumberOfHandNeeded(): int
    {
        return $this->numberOfHandNeeded;
    }

    public function setNumberOfHandNeeded(int $numberOfHandNeeded): void
    {
        $this->numberOfHandNeeded = $numberOfHandNeeded;
    }

}

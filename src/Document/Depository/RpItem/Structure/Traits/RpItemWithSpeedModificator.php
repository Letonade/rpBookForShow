<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Common\Range\Range;

trait RpItemWithSpeedModificator
{
    /**
     * @var Range
     * @MongoDB\EmbedOne (targetDocument=Range::class)
     */
    protected $speedModificator;

    public function getSpeedModificator(): Range
    {
        return $this->speedModificator;
    }

    public function setSpeedModificator(Range $speedModificator): void
    {
        $this->speedModificator = $speedModificator;
    }


}

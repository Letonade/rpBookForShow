<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Common\Range\Range;

trait RpItemWithRange
{
    /**
     * @var Range
     * @MongoDB\EmbedOne (targetDocument=Range::class)
     */
    protected $range;

    public function getRange(): Range
    {
        return $this->range;
    }

    public function setRange(Range $range): void
    {
        $this->range = $range;
    }



}

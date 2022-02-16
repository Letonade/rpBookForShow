<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Common\Range\Range;

trait RpItemWithFixedReach
{
    /**
     * @var Range
     * @MongoDB\EmbedOne (targetDocument=Range::class, name="fixed_reach")
     */
    protected $fixedReach;

    public function getFixedReach(): Range
    {
        return $this->fixedReach;
    }

    public function setFixedReach(Range $fixedReach): void
    {
        $this->fixedReach = $fixedReach;
    }


}

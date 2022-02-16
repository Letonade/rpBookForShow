<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\BattleWare;

use App\Document\Depository\RpItem\Structure\RpItem;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithBulk;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithLevel;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManyCapacityAndUsage;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithRange;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManySpecial;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Repository\Depository\RpItemRepository;

/**
 * @MongoDB\Document()
 **/
class GrenadeRpItem extends RpItem
{
    use RpItemWithLevel;
    use RpItemWithBulk;
    use RpItemWithRange;
    use RpItemWithManyCapacityAndUsage;
    use RpItemWithManySpecial;

    const DOCUMENT_NAME = 'GrenadeRpItem';

    public function __construct()
    {
        parent::__construct();
        $this->capacityAndUsages = new ArrayCollection();
        $this->specials = new ArrayCollection();
    }

}

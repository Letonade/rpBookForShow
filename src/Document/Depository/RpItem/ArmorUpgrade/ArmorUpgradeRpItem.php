<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\ArmorUpgrade;

use App\Document\Depository\RpItem\Structure\RpItem;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithArmorTypes;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithBulk;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithLevel;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManyCapacityAndUsage;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithNumberOfUpgradeSlot;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 **/
class ArmorUpgradeRpItem extends RpItem
{
    use RpItemWithBulk;
    use RpItemWithLevel;
    use RpItemWithNumberOfUpgradeSlot;
    use RpItemWithManyCapacityAndUsage;
    use RpItemWithArmorTypes;

    const DOCUMENT_NAME = 'ArmorUpgradeRpItem';

    public function __construct()
    {
        parent::__construct();
        $this->capacityAndUsages = new ArrayCollection();
        $this->armorTypes = new ArrayCollection();
    }

}

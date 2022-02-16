<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Armor;

use App\Document\Depository\RpItem\Structure\RpItem;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithArmorCheckModificator;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithArmorClassMaximumDexterityModificator;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithArmorClassModificator;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithBulk;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithLevel;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithNumberOfUpgradeSlot;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithSpeedModificator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 **/
class LightArmorRpItem extends RpItem
{
    use RpItemWithLevel;
    use RpItemWithArmorClassModificator;
    use RpItemWithArmorClassMaximumDexterityModificator;
    use RpItemWithArmorCheckModificator;
    use RpItemWithSpeedModificator;
    use RpItemWithNumberOfUpgradeSlot;
    use RpItemWithBulk;

    const DOCUMENT_NAME = 'LightArmorRpItem';

    public function __construct()
    {
        parent::__construct();
    }

}

<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Armor;

use App\Document\Depository\RpItem\Structure\RpItem;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithArmorCheckModificator;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithArmorClassMaximumDexterityModificator;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithArmorClassModificator;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithBulk;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithFixedStrength;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManyDamage;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithFixedReach;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithLevel;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManyCapacityAndUsage;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithNumberOfUpgradeSlot;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithNumberOfWeaponSlot;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithSize;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithSpeedModificator;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManySpeedMovement;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 **/
class PoweredArmorRpItem extends RpItem
{
    use RpItemWithLevel;
    use RpItemWithArmorClassModificator;
    use RpItemWithArmorClassMaximumDexterityModificator;
    use RpItemWithArmorCheckModificator;
    use RpItemWithFixedStrength;
    use RpItemWithSize;
    use RpItemWithFixedReach;
    use RpItemWithNumberOfUpgradeSlot;
    use RpItemWithNumberOfWeaponSlot;
    use RpItemWithBulk;
    use RpItemWithManySpeedMovement;
    use RpItemWithManyCapacityAndUsage;
    use RpItemWithManyDamage;

    const DOCUMENT_NAME = 'PoweredArmorRpItem';

    public function __construct()
    {
        parent::__construct();
        $this->capacityAndUsages = new ArrayCollection();
        $this->speedMovements = new ArrayCollection();
        $this->damages = new ArrayCollection();
    }

}

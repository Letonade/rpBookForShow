<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Weapon;

use App\Document\Depository\RpItem\Structure\RpItem;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithBulk;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManyCritical;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithLevel;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManyModificatorDamage;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManySpecial;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 **/
class SolarianWeaponCrystalRpItem extends RpItem
{
    use RpItemWithLevel;
    use RpItemWithBulk;
    use RpItemWithManyModificatorDamage;
    use RpItemWithManyCritical;
    use RpItemWithManySpecial;

    const DOCUMENT_NAME = 'SolarianWeaponCrystalRpItem';

    public function __construct()
    {
        parent::__construct();
        $this->modificatorDamages = new ArrayCollection();
        $this->specials = new ArrayCollection();
        $this->criticals = new ArrayCollection();
    }

}

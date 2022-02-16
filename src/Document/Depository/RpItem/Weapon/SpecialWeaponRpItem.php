<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Weapon;

use App\Document\Depository\RpItem\Structure\RpItem;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithBulk;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManyCritical;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManyDamage;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithLevel;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManyCapacityAndUsage;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithNumberOfHandNeeded;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithRange;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManySpecial;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithWeaponCategory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 **/
class SpecialWeaponRpItem extends RpItem
{
    use RpItemWithWeaponCategory;
    use RpItemWithLevel;
    use RpItemWithManyDamage;
    use RpItemWithRange;
    use RpItemWithBulk;
    use RpItemWithNumberOfHandNeeded;
    use RpItemWithManyCapacityAndUsage;
    use RpItemWithManyCritical;
    use RpItemWithManySpecial;

    const DOCUMENT_NAME = 'SpecialWeaponRpItem';

    public function __construct()
    {
        parent::__construct();
        $this->capacityAndUsages = new ArrayCollection();
        $this->damages = new ArrayCollection();
        $this->specials = new ArrayCollection();
        $this->criticals = new ArrayCollection();
    }

}

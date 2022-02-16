<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\BattleWare;

use App\Document\Depository\RpItem\Structure\RpItem;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithAmmunitionType;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithBulk;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithCartridge;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithIsStandard;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithLevel;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithManySpecial;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 **/
class AmmunitionRpItem extends RpItem
{
    use RpItemWithIsStandard;
    use RpItemWithLevel;
    use RpItemWithCartridge;
    use RpItemWithBulk;
    use RpItemWithManySpecial;
    use RpItemWithAmmunitionType;

    public const DOCUMENT_NAME = 'AmmunitionRpItem';

    public function __construct()
    {
        parent::__construct();
        $this->specials = new ArrayCollection();
    }

}

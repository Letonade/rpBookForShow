<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Fusion;

use App\Document\Depository\RpItem\Structure\RpItem;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithDescription;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithLevel;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 **/
class WeaponFusionRpItem extends RpItem
{
    use RpItemWithDescription;
    use RpItemWithLevel;

    const DOCUMENT_NAME = 'WeaponFusionRpItem';

    public function __construct()
    {
        parent::__construct();
    }

}

<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem;

use App\Document\Depository\RpItem\Structure\RpItem;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithLevel;
use App\Document\Depository\RpItem\Structure\Traits\RpItemWithSystem;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document()
 **/
class AugmentRpItem extends RpItem
{
    use RpItemWithLevel;
    use RpItemWithSystem;

    public const DOCUMENT_NAME = 'AugmentRpItem';
}

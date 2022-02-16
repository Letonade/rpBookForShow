<?php
/**
 * @author Letonade
 * @date   17/11/2021 15:54
 */

namespace App\Service\RpItemPropertiesAsString;


use Doctrine\ODM\MongoDB\DocumentManager;

class RpItemPropertiesAsStringMapper
{
    public const SPECIAL_LABEL_THAT_CAN_HAVE_SUB_SPECIALS = [
        'Explode'
    ];
    public const CRITICAL_LABEL_THAT_CAN_HAVE_SUB_SPECIALS = [
    ];

    use RangeStringMapper;
    use DamageStringMapper;
    use ModificatorDamageStringMapper;
    use CriticalStringMapper;
    use SpecialStringMapper;
    use CapacityAndUsageAsStringMapper;
    use RpItemSpeedStringMapper;

    /**
     * @var DocumentManager
     */
    private $documentManager;

    public function __construct(
        DocumentManager $documentManager
    ) {
        $this->documentManager = $documentManager;
    }
}

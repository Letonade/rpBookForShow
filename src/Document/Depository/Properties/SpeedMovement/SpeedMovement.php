<?php

declare(strict_types=1);

namespace App\Document\Depository\Properties\SpeedMovement;

use App\Document\Depository\Depository;
use App\Document\Book\Book;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsInterface;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsTrait;
use App\Document\Common\Extrafield\Extrafield;
use App\Document\Common\Range\Range;
use App\Service\RpItemPropertiesAsString\RpItemSpeedStringMapper;
use App\Service\RpItemPropertiesAsString\SpecialStringMapper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Document\Depository\Properties\Special\SpecialType;
use Exception;

/**
 * @MongoDB\EmbeddedDocument()
 */
class SpeedMovement
{
    /**
     * @var string
     * @MongoDB\Id(type="id")
     */
    protected $id;

    //Ex: "1d8+2, fire" wil get "Explosion(1d8+2, fire)"
    /**
     * @var Range
     * @MongoDB\EmbedOne (targetDocument=Range::class)
     */
    protected $range;

    /**
     * @var SpeedMovementType
     * @MongoDB\ReferenceOne (targetDocument=SpeedMovementType::class, storeAs="id", name="speed_movement_type")
     */
    protected $speedMovementType;

    public function __construct()
    {
        $this->otherSpecialReferences = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getRange(): Range
    {
        return $this->range;
    }

    public function setRange(Range $range): void
    {
        $this->range = $range;
    }

    public function getSpeedMovementType(): SpeedMovementType
    {
        return $this->speedMovementType;
    }

    public function setSpeedMovementType(SpeedMovementType $speedMovementType): void
    {
        $this->speedMovementType = $speedMovementType;
    }

    public function __toString()
    {
        return RpItemSpeedStringMapper::getRpItemSpeedMovementAsString();
    }


}

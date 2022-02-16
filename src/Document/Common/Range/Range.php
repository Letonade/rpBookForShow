<?php
/**
 * @author Letonade
 * @date   18/11/2021 17:41
 */

namespace App\Document\Common\Range;


use App\Service\RpItemPropertiesAsString\RangeStringMapper;
use App\Service\RpRangeConverter\RpRangeConverter;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument()
 */
class Range
{
    /**
     * @var float
     * @MongoDB\Field(type="float")
     */
    protected $valueInSquare;

    /**
     * @var float
     * @MongoDB\Field(type="float")
     */
    protected $valueInFeet;

    /**
     * @var float
     * @MongoDB\Field(type="float")
     */
    protected $valueInMeter;

    public function getValueInFeet(): float
    {
        return $this->valueInFeet;
    }

    public function setValueInFeet(float $valueInFeet): void
    {
        $this->valueInFeet = $valueInFeet;
        $converter = new RpRangeConverter();
        $this->valueInMeter = $converter->convert($valueInFeet, RpRangeConverter::FEET_ACRONYM, RpRangeConverter::METER_ACRONYM);
        $this->valueInSquare = $converter->convert($valueInFeet, RpRangeConverter::FEET_ACRONYM, RpRangeConverter::SQUARE_ACRONYM);
    }

    public function getValueInMeter(): float
    {
        return $this->valueInMeter;
    }

    public function setValueInMeter(float $valueInMeter): void
    {
        $this->valueInMeter = $valueInMeter;
        $converter = new RpRangeConverter();
        $this->valueInFeet = $converter->convert($valueInMeter, RpRangeConverter::METER_ACRONYM, RpRangeConverter::FEET_ACRONYM);
        $this->valueInSquare = $converter->convert($valueInMeter, RpRangeConverter::METER_ACRONYM, RpRangeConverter::SQUARE_ACRONYM);
    }

    public function getValueInSquare(): float
    {
        return $this->valueInSquare;
    }

    public function setValueInSquare(float $valueInSquare): void
    {
        $this->valueInSquare = $valueInSquare;
        $converter = new RpRangeConverter();
        $this->valueInMeter = $converter->convert($valueInSquare, RpRangeConverter::SQUARE_ACRONYM, RpRangeConverter::METER_ACRONYM);
        $this->valueInFeet = $converter->convert($valueInSquare, RpRangeConverter::SQUARE_ACRONYM, RpRangeConverter::FEET_ACRONYM);
    }

    public function __toString()
    {
        return $this->getValueInSquare().' sq';
    }

}

<?php
/**
 * @author Letonade
 * @date   06/11/2021 19:56
 */

namespace App\Service\RpItemPropertiesAsString;

use App\Document\Common\Range\Range;
use App\Service\RpRangeConverter\RpRangeConverter;
use Exception;
use Symfony\Component\String\UnicodeString;

trait RangeStringMapper
{
    public function getRangeFromString(string $rangeString): ?Range
    {
        $converter = new RpRangeConverter();

        $foundUnit = null;
        foreach (RpRangeConverter::MEASURE_UNIT_ARRAY as $measureUnit){
            if (strpos($rangeString, $measureUnit)){
                if ($foundUnit !== null){
                    return null;
                }
                $foundUnit = $measureUnit;
            }
        }
        if (!$foundUnit){
            throw new Exception('Not a valid range string.');
        }
        $rangeString = new UnicodeString(preg_replace('/\s+/', '', $rangeString));
        $rangeString = $rangeString->before($foundUnit);
        if(!$rangeString->toString() === $rangeString->match("/^[0-9]+/")[0]){
            throw new Exception('Not a valid range string.');
        }
        $foundValue = (int)$rangeString->toString();
        $range = new Range();
        $range->setValueInFeet($converter->convert($foundValue, $foundUnit, RpRangeConverter::FEET_ACRONYM));

        return $range;
    }
}

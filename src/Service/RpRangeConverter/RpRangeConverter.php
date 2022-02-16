<?php
/**
 * @author Letonade
 * @date   17/11/2021 15:54
 */

namespace App\Service\RpRangeConverter;

use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;

class RpRangeConverter
{
    public const SQUARE_ACRONYM = 'c';
    public const  METER_ACRONYM = 'm';
    public const   FEET_ACRONYM = 'ft.';

    public const   FEET_TO_METER_MULTIPLICATOR = [self::FEET_ACRONYM . self::METER_ACRONYM => 0.3];
    public const   METER_TO_FEET_MULTIPLICATOR = [self::METER_ACRONYM . self::FEET_ACRONYM => 5 / 1.5];
    public const  FEET_TO_SQUARE_MULTIPLICATOR = [self::FEET_ACRONYM . self::SQUARE_ACRONYM => 0.2];
    public const  SQUARE_TO_FEET_MULTIPLICATOR = [self::SQUARE_ACRONYM . self::FEET_ACRONYM => 5];
    public const METER_TO_SQUARE_MULTIPLICATOR = [self::METER_ACRONYM . self::SQUARE_ACRONYM => 1 / 1.5];
    public const SQUARE_TO_METER_MULTIPLICATOR = [self::SQUARE_ACRONYM . self::METER_ACRONYM => 1.5];

    public const MEASURE_UNIT_ARRAY = [
        self::SQUARE_ACRONYM,
        self::METER_ACRONYM,
        self::FEET_ACRONYM,
    ];

    public const MEASURE_UNIT_CONVERTER_ARRAY = [
        self::  FEET_TO_METER_MULTIPLICATOR,
        self::  METER_TO_FEET_MULTIPLICATOR,
        self:: FEET_TO_SQUARE_MULTIPLICATOR,
        self:: SQUARE_TO_FEET_MULTIPLICATOR,
        self::METER_TO_SQUARE_MULTIPLICATOR,
        self::SQUARE_TO_METER_MULTIPLICATOR,
    ];

    public function convert(float $value, string $inputUnit, string $outputUnit)
    {
        if (!$this->validateUnit($inputUnit) || !$this->validateUnit($outputUnit)) {
            throw new Exception("Unit not found.");
        }
        if ($inputUnit === $outputUnit){
            return $value;
        }
        $converterAcronym = $inputUnit.$outputUnit;
        $converterValue = null;
        foreach (self::MEASURE_UNIT_CONVERTER_ARRAY as $converter) {
            if (array_key_first($converter) === $converterAcronym){
                $converterValue = $converter[array_key_first($converter)];
                break;
            }
        }
        return $converterValue * $value;
    }

    private function validateUnit(string $unit): bool
    {
        if (!in_array($unit, self::MEASURE_UNIT_ARRAY)) {
            return false;
        }
        return true;
    }

}

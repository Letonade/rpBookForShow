<?php

declare(strict_types=1);

namespace App\Document\Depository\Properties\CapacityType;

use App\Service\RpItemPropertiesAsString\CapacityAndUsageAsStringMapper;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Exception;

/**
 * @MongoDB\EmbeddedDocument
 */
class CapacityAndUsage
{
    const RATE_USE = 'use';
    const RATE_ROUND = 'round';
    const RATE_ACTION = 'action';
    const RATE_SECOND = 'second';
    const RATE_MINUTE = 'minute';
    const RATE_HOUR = 'hour';
    const RATE_DAY = 'day';
    const RATE_MONTH = 'month';
    const RATE_YEAR = 'year';
    const RATES = [
        self::RATE_USE,
        self::RATE_ROUND,
        self::RATE_ACTION,
        self::RATE_SECOND,
        self::RATE_MINUTE,
        self::RATE_HOUR,
        self::RATE_DAY,
        self::RATE_MONTH,
        self::RATE_YEAR,
    ];

    /**
     * @var int
     * @MongoDB\Field(type="int")
     */
    private $capacity;

    /**
     * @var int|null
     * @MongoDB\Field(type="int")
     */
    private $usage;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    private $rate = self::RATE_USE;

    /**
     * @var CapacityType
     * @MongoDB\ReferenceOne (targetDocument=CapacityType::class, storeAs="id", name="capacity_type")
     */
    protected $capacityType;

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): void
    {
        $this->capacity = $capacity;
    }

    public function getCapacityType(): CapacityType
    {
        return $this->capacityType;
    }

    public function setCapacityType(CapacityType $capacityType): void
    {
        $this->capacityType = $capacityType;
    }

    public function getUsage(): ?int
    {
        return $this->usage;
    }

    public function setUsage(?int $usage): void
    {
        $this->usage = $usage;
    }

    public function __toString()
    {
        if ($this->usage > 1) {
            return $this->capacity . '/' . $this->usage . ' ' . $this->capacityType->getPlural();
        } else {
            return $this->capacity . '/' . $this->usage . ' ' . $this->capacityType->getName();
        }
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function setRate(string $rate): void
    {
        if (!in_array($rate, self::RATES)){
            throw new Exception($rate." is not a valid rate of use");
        }
        $this->rate = $rate;
    }

}

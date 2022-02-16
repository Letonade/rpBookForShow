<?php

declare(strict_types=1);

namespace App\Document\Common\Assess;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class Assess
{
    /**
     * @var int
     * @MongoDB\Field(type="int")
     */
    private $value;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    private $currency;

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function __toString()
    {
        return $this->value.' '.$this->currency;
    }
}

<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

trait RpItemWithBulk
{
    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $bulk = '-';

    public function getBulk(): string
    {
        return $this->bulk;
    }

    public function setBulk(string $bulk): void
    {
        $this->bulk = $bulk;
    }
}

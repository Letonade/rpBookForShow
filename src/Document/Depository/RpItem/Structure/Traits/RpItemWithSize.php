<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Depository\Properties\Size\Size;

trait RpItemWithSize
{
    /**
     * @var Size
     * @MongoDB\ReferenceOne (targetDocument=Size::class, storeAs="id")
     */
    protected $size;

    public function getSize(): Size
    {
        return $this->size;
    }

    public function setSize(Size $size): void
    {
        $this->size = $size;
    }

}

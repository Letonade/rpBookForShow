<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

trait RpItemWithCartridge
{
    /**
     * @var int
     * @MongoDB\Field(type="int")
     */
    protected $cartridge = 1;

    public function getCartridge(): int
    {
        return $this->cartridge;
    }

    public function setCartridge(int $cartridge): void
    {
        $this->cartridge = $cartridge;
    }

}

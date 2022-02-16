<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

trait RpItemWithArmorClassMaximumDexterityModificator
{
    /**
     * @var int
     * @MongoDB\Field(type="int", name="maximum_dexterity_modificator")
     */
    protected $maximumDexterityModificator = 0;

    public function getMaximumDexterityModificator(): int
    {
        return $this->maximumDexterityModificator;
    }

    public function setMaximumDexterityModificator(int $maximumDexterityModificator): void
    {
        $this->maximumDexterityModificator = $maximumDexterityModificator;
    }

}

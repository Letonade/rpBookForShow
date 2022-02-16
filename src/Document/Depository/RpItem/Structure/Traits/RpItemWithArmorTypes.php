<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Depository\Properties\ArmorType\ArmorType;

trait RpItemWithArmorTypes
{
    /**
     * @var Collection|ArmorType[]
     * @MongoDB\ReferenceMany  (targetDocument=ArmorType::class, storeAs="id", name="armor_types")
     */
    protected $armorTypes;

    public function getArmorTypes()
    {
        return $this->armorTypes;
    }

    public function setArmorTypes($armorTypes): void
    {
        $this->armorTypes = $armorTypes;
        $this->setExtrafieldArmorTypesString();
    }

    public function addArmorType(ArmorType $armorType)
    {
        $this->armorTypes[] = $armorType;
        $this->setExtrafieldArmorTypesString();
    }

    public function removeArmorType(ArmorType $armorType)
    {
        if (false !== $key = array_search($armorType, $this->armorTypes, true)) {
            array_splice($this->armorTypes, $key, 1);
        }
        $this->setExtrafieldArmorTypesString();
    }

    private function setExtrafieldArmorTypesString(){
        $string = '';
        foreach ($this->armorTypes as $key => $armorType){
            if ($key !== 0){
                $string .= ', ';
            }
            $string .= $armorType->getName();

        }
        $this->setExtrafieldByHandle('armorTypesString', $string);
    }

}

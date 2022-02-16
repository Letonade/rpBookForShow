<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Depository\Properties\CapacityType\CapacityAndUsage;
use App\Document\Depository\Properties\CapacityType\CapacityType;

// Weapon amunition is a kind of capacity
trait RpItemWithManyCapacityAndUsage
{
    /**
     * @var Collection|CapacityAndUsage[]
     * @MongoDB\EmbedMany (targetDocument=CapacityAndUsage::class, name="capacity_and_usages")
     */
    protected $capacityAndUsages;

    public function getCapacityAndUsages()
    {
        return $this->capacityAndUsages;
    }

    public function setCapacityAndUsages($capacityAndUsages): void
    {
        $this->capacityAndUsages = $capacityAndUsages;
        $this->setExtrafieldCapacityAndUsageString();
    }

    public function addCapacityAndUsage(CapacityAndUsage $ammunitionCapacityAndUsage)
    {
        $this->capacityAndUsages[] = $ammunitionCapacityAndUsage;
        $this->setExtrafieldCapacityAndUsageString();
    }

    public function removeCapacityAndUsage(CapacityAndUsage $ammunitionCapacityAndUsage)
    {
        if (false !== $key = array_search($ammunitionCapacityAndUsage, $this->capacityAndUsages, true)) {
            array_splice($this->capacityAndUsages, $key, 1);
        }
        $this->setExtrafieldCapacityAndUsageString();
    }

    private function setExtrafieldCapacityAndUsageString(){
        $string = '';
        foreach ($this->capacityAndUsages as $key => $usage){
            if ($key !== 0){
                $string .= ', ';
            }
            if ($usage->getUsage() > 1){
                $string .= $usage->getCapacity().'/'.$usage->getUsage().' '.$usage->getCapacityType()->getPlural();
            }else{
                $string .= $usage->getCapacity().'/'.$usage->getUsage().' '.$usage->getCapacityType()->getName();
            }
        }
        $this->setExtrafieldByHandle('capacityAndUsagesString', $string);
    }
}

<?php
/**
 * @author Letonade
 * @date   06/11/2021 19:56
 */

namespace App\Service\RpItemPropertiesAsString;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\CapacityType\CapacityAndUsage;
use App\Document\Depository\Properties\CapacityType\CapacityType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\String\UnicodeString;
use Exception;

trait CapacityAndUsageAsStringMapper
{
    public function getCapacitiesAndUsagesFromString(string $capacity, string $usage, Depository $depository, string $rate = CapacityAndUsage::RATE_USE): ArrayCollection
    {
        $cuColl = new ArrayCollection();
        $cuColl->add($this->getCapacityAndUsageFromString($capacity, $usage, $depository, $rate));

        return $cuColl;
    }

    public function getCapacityAndUsageFromString(string $capacity, string $usage, Depository $depository, string $rate = CapacityAndUsage::RATE_USE)
    {
        $capAndUse = new CapacityAndUsage();
        if (strtolower($capacity) === 'drawn'){
            $capacityType = $this->documentManager->getRepository(CapacityType::class)->findOneBy(["name" => strtolower($capacity)]);
            if (!$capacityType instanceof CapacityType){
                throw new Exception("Drawn not found:".strtolower($capacity));
            }
            $capAndUse->setCapacityType($capacityType);
            $capAndUse->setUsage(1);
            $capAndUse->setCapacity(1);
            $capAndUse->setRate($rate);
            return $capAndUse;
        }
        $uniCapacity = new UnicodeString($capacity);
        $uniUsage = new UnicodeString($usage);
        $capAndUse->setCapacity($uniCapacity->match("/^[0-9]+/")[0]);
        $capAndUse->setUsage($uniUsage->match("/^[0-9]+/")[0]);
        $capAndUse->setRate($rate);
        $uniType = trim(preg_replace("/^[0-9]+/", "", $uniCapacity->toString()));
        if (substr($uniType, -1) === 's'){
            $uniType = substr($uniType, 0, -1);
        }

        $capacityType = $this->documentManager->getRepository(CapacityType::class)->findOneBy(["name" => $uniType]);
        if (!$capacityType)
        {
            throw new Exception("Capacity type not found:".$uniType);
        }
        $capAndUse->setCapacityType($capacityType);
        return $capAndUse;
    }
}

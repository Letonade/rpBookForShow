<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Common\Damage\ModificatorDamage;
use App\Service\RpItemPropertiesAsString\ModificatorDamageStringMapper;
use Doctrine\Common\Collections\Collection;

trait RpItemWithManyModificatorDamage
{
    /**
     * @var Collection|ModificatorDamage[]
     * @MongoDB\EmbedMany  (targetDocument=ModificatorDamage::class, name="modificator_damages")
     */
    protected $modificatorDamages;

    public function getModificatorDamages()
    {
        return $this->modificatorDamages;
    }

    public function setModificatorDamages($modificatorDamages): void
    {
        $this->modificatorDamages = $modificatorDamages;
        $this->setExtrafieldDamagesString();
        $this->setExtrafieldRawMaximumDamages();
    }

    public function addModificatorDamage(ModificatorDamage $modificatorDamage)
    {
        $this->modificatorDamages[] = $modificatorDamage;
        $this->setExtrafieldDamagesString();
        $this->setExtrafieldRawMaximumDamages();
    }

    public function removeModificatorDamage(ModificatorDamage $modificatorDamage)
    {
        if (false !== $key = array_search($modificatorDamage, $this->modificatorDamages, true)) {
            array_splice($this->modificatorDamages, $key, 1);
        }
        $this->setExtrafieldDamagesString();
        $this->setExtrafieldRawMaximumDamages();
    }

    private function setExtrafieldDamagesString(){
        $string = '';
        foreach ($this->modificatorDamages as $key => $modDam){
            if ($key !== 0){
                $string .= ' or ';
            }
            $string .= ModificatorDamageStringMapper::getModificatorDamageAsString($modDam);
        }
        $this->setExtrafieldByHandle('modificatorDamagesString', $string);
    }

    private function setExtrafieldRawMaximumDamages()
    {
        $itemRawMaxDam = 0;
        $itemRawMinDam = 0;
        foreach ($this->modificatorDamages as $key => $dam) {
            $rawMaxDam = (($dam->getDamageDiceAmount() * $dam->getDamageDiceFaceCount()) + $dam->getDamageFixedAmount());
            if ($itemRawMaxDam < $rawMaxDam) {
                $itemRawMaxDam = $rawMaxDam;
            }
            if ($itemRawMinDam === 0){
                $itemRawMinDam = $itemRawMaxDam;
            }
            $rawMinDam = ($dam->getDamageDiceAmount() + $dam->getDamageFixedAmount());
            if ($itemRawMinDam > $rawMinDam) {
                $itemRawMinDam = $rawMinDam;
            }
        }
        $this->setExtrafieldByHandle('modRawMaximumDamage', (string)$itemRawMaxDam);
        $this->setExtrafieldByHandle('modRawMinimumDamage', (string)$itemRawMinDam);
    }
}

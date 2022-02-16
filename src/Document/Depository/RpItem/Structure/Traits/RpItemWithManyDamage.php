<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Common\Damage\Damage;
use App\Document\Common\Extrafield\Extrafield;
use App\Service\RpItemPropertiesAsString\DamageStringMapper;
use Doctrine\Common\Collections\Collection;

trait RpItemWithManyDamage
{
    /**
     * @var Collection|Damage[]
     * @MongoDB\EmbedMany (targetDocument=Damage::class)
     */
    protected $damages;

    public function getDamages()
    {
        return $this->damages;
    }

    public function setDamages($damages): void
    {
        $this->damages = $damages;
        $this->setExtrafieldDamagesString();
        $this->setExtrafieldRawMaximumDamages();
    }

    public function addDamage(Damage $damage)
    {
        $this->damages[] = $damage;
        $this->setExtrafieldDamagesString();
        $this->setExtrafieldRawMaximumDamages();
    }

    public function removeDamage(Damage $damage)
    {
        if (false !== $key = array_search($damage, $this->damages, true)) {
            array_splice($this->damages, $key, 1);
        }
        $this->setExtrafieldDamagesString();
        $this->setExtrafieldRawMaximumDamages();
    }

    private function setExtrafieldDamagesString()
    {
        $string = '';
        foreach ($this->damages as $key => $dam) {
            if ($key !== 0) {
                $string .= ' or ';
            }
            $string .= DamageStringMapper::getDamageAsString($dam);
        }
        $this->setExtrafieldByHandle('damagesString', $string);
    }

    private function setExtrafieldRawMaximumDamages()
    {
        $itemRawMaxDam = 0;
        $itemRawMinDam = 0;
        foreach ($this->damages as $key => $dam) {
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
        $this->setExtrafieldByHandle('rawMaximumDamage', (string)$itemRawMaxDam);
        $this->setExtrafieldByHandle('rawMinimumDamage', (string)$itemRawMinDam);
    }

}

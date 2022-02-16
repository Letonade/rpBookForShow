<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Depository\Properties\Special\Special;
use App\Service\RpItemPropertiesAsString\SpecialStringMapper;
use Doctrine\Common\Collections\Collection;

trait RpItemWithManySpecial
{
    /**
     * @var Collection|Special[]
     * @MongoDB\EmbedMany (targetDocument=Special::class)
     */
    protected $specials;

    public function getSpecials()
    {
        return $this->specials;
    }

    public function setSpecials($specials): void
    {
        $this->specials = $specials;
        $this->setExtrafieldSpecialString();
    }

    public function addSpecial(Special $special)
    {
        $this->specials->add($special);
        $this->setExtrafieldSpecialString();
    }

    public function removeSpecial(Special $special)
    {
        $this->specials->removeElement($special);
        $this->setExtrafieldSpecialString();
    }

    private function setExtrafieldSpecialString(){
        $string = '';
        foreach ($this->specials as $key => $spe){
            if ($key !== 0){
                $string .= ', ';
            }
            $string .= SpecialStringMapper::getSpecialAsString($spe);
        }
        $this->setExtrafieldByHandle('specialsString', $string);
    }


}

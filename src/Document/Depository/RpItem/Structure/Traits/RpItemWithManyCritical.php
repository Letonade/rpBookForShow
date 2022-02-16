<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Depository\Properties\Critical\Critical;
use App\Service\RpItemPropertiesAsString\CriticalStringMapper;
use Doctrine\Common\Collections\Collection;

trait RpItemWithManyCritical
{
    /**
     * @var Collection|Critical[]
     * @MongoDB\EmbedMany (targetDocument=Critical::class)
     */
    protected $criticals;

    public function getCriticals()
    {
        return $this->criticals;
    }

    public function setCriticals($criticals): void
    {
        $this->criticals = $criticals;
        $this->setExtrafieldCriticalString();
    }

    public function addCritical(Critical $critical)
    {
        $this->criticals->add($critical);
        $this->setExtrafieldCriticalString();
    }

    public function removeCritical(Critical $critical)
    {
        $this->criticals->removeElement($critical);
        $this->setExtrafieldCriticalString();
    }

    private function setExtrafieldCriticalString(){
        $string = '';
        foreach ($this->criticals as $key => $crit){
            if ($key !== 0){
                $string .= ', ';
            }
            $string .= CriticalStringMapper::getCriticalAsString($crit);
        }
        $this->setExtrafieldByHandle('criticalsString', $string);
    }


}

<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure\Traits;

use App\Document\Depository\Properties\Special\Special;
use App\Document\Depository\Properties\SpeedMovement\SpeedMovement;
use App\Service\RpItemPropertiesAsString\RpItemSpeedStringMapper;
use App\Service\RpItemPropertiesAsString\SpecialStringMapper;
use Doctrine\Common\Collections\Collection;

trait RpItemWithManySpeedMovement
{
    /**
     * @var Collection|SpeedMovement[]
     * @MongoDB\EmbedMany (targetDocument=SpeedMovement::class, name="speed_movements")
     */
    protected $speedMovements;

    public function getSpeedMovements()
    {
        return $this->speedMovements;
    }

    public function setSpeedMovements($speedMovements): void
    {
        $this->speedMovements = $speedMovements;
        $this->setExtrafieldSpeedMovementString();
    }

    public function addSpeedMovement(SpeedMovement $speedMovements)
    {
        $this->speedMovements->add($speedMovements);
        $this->setExtrafieldSpeedMovementString();
    }

    public function removeSpeedMovement(SpeedMovement $speedMovements)
    {
        $this->speedMovements->removeElement($special);
        $this->setExtrafieldSpeedMovementString();
    }

    private function setExtrafieldSpeedMovementString(){
        $string = '';
        foreach ($this->speedMovements as $key => $spe){
            if ($key !== 0){
                $string .= ', ';
            }
            $string .= RpItemSpeedStringMapper::getRpItemSpeedMovementAsString($spe);
        }
        $this->setExtrafieldByHandle('speedMovementString', $string);
    }


}

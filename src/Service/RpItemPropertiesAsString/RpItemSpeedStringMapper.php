<?php
/**
 * @author Letonade
 * @date   06/11/2021 19:56
 */

namespace App\Service\RpItemPropertiesAsString;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\SpeedMovement\SpeedMovement;
use App\Document\Depository\Properties\SpeedMovement\SpeedMovementType;
use App\Document\Common\Range\Range;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

trait RpItemSpeedStringMapper
{
    public function getRpItemSpeedMovementsFromString(string $speedMovementString, Depository $depository): ?ArrayCollection
    {
        // $split = "/([(].*?[)])|(\w)+/";
        $split = "/([(].*?[)])|([a-zA-Z0-9_ +-])+/";
        preg_match_all($split, $speedMovementString, $speedMovementArray);
        $speedMovementArray = $speedMovementArray[0];
        $speedMovementAssocArray = [];
        foreach ($speedMovementArray as $key => $part) {
            if (ord($part) === 151 || ord($part) === 45){
                continue;// When no damage string is a Char(151) like a "-"
            }
            $part = trim($part);
            if (substr($part, 0, 1) === '(') {
                continue;
            }
            // dump($part, strcspn( $part , '0123456789' ), $part[strcspn( $part , '0123456789' )]);
            if (strcspn( $part , '0123456789' ) === 0) {
                $type = 'Overland';
            }else {
                $type = trim (strstr($part, $part[strcspn( $part , '0123456789' )], true));
                $part = trim (strstr($part, $part[strcspn( $part , '0123456789' )]));
            }
            $speedMovementAssocArray[$type] = (int)$part;
        }

        $speedMovements = new ArrayCollection();
        foreach ($speedMovementAssocArray as $speedMovementName => $speedMovementInnerString) {
            $speedMovementType = $this->documentManager->getRepository(SpeedMovementType::class)->findCaseInsentive('name' , $speedMovementName);
            if (!$speedMovementType) {
                throw new Exception('This speed movement type have not been found, please import it beforehand : ' . $speedMovementName);
            }
            $range = new Range();
            $range->setValueInFeet((int)$speedMovementInnerString);
            $speedMovement = new SpeedMovement();
            $speedMovement->setSpeedMovementType($speedMovementType);
            $speedMovement->setRange($range);
            $speedMovements->add($speedMovement);
        }
        return $speedMovements;
    }

    public function getRpItemSpeedMovementsAsString(ArrayCollection $speedMovements): string
    {
        $asString = '';
        /** @var SpeedMovement $speedMovement */
        foreach ($speedMovements as $speedMovement) {
            if ($asString !== '') {
                $asString .= ", ";
            }
            $asString .= $this->getRpItemSpeedMovementAsString($speedMovement);
        }

        return $asString;
    }

    public static function getRpItemSpeedMovementAsString(SpeedMovement $speedMovement): string
    {
        $asString = '';
        $name = $speedMovement->getSpeedMovementType()->getName();
        if ($name === "overland"){$name = '';}
        $asString .= sprintf("%s %d Sq ",
            $name,
            $speedMovement->getRange()->getValueInSquare()
        );
        $asString = trim($asString);

        return $asString;
    }
}

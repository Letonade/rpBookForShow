<?php
/**
 * @author Letonade
 * @date   06/11/2021 19:56
 */

namespace App\Service\RpItemPropertiesAsString;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\ConditionType\ConditionType;
use App\Document\Depository\Properties\Special\Special;
use App\Document\Depository\Properties\Special\SpecialType;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\String\UnicodeString;

trait SpecialStringMapper
{
    public function getSpecialsFromString(string $specialsString, Depository $depository): ?ArrayCollection
    {
        // $split = "/([(].*?[)])|(\w)+/";
        $split = "/([(].*?[)])|([a-zA-Z0-9_ +-])+/";
        preg_match_all($split, $specialsString, $specialArray);
        $specialArray = $specialArray[0];
        $specialAssocArray = [];
        foreach ($specialArray as $key => $part) {
            if (ord($part) === 151 || ord($part) === 45) {
                continue;// When no damage string is a Char(151) like a "-"
            }
            $part = trim($part);
            if (substr($part, 0, 1) === '(') {
                continue;
            }

            $diceSplit = "/((\d+)(\d*)d(\d*)([-+*\/><]?\d*))/";// -> find dice
            $trueDamageSplit = "/(\d+)$/";// -> true damage
            preg_match($diceSplit, $part, $damagePart);
            $partInWordsArray = explode(' ', $part);
            preg_match($trueDamageSplit, $part, $trueDamagePart);
            if (isset($damagePart[0])) {
                $part = preg_replace($diceSplit, "", $part);
                $part = trim($part);
                $specialAssocArray[$part] = $damagePart[0];
            } elseif ($partInWordsArray[array_key_last($partInWordsArray)] === reset($trueDamagePart)) {
                array_splice($partInWordsArray, -1);
                $specialAssocArray[trim(implode(" ", $partInWordsArray))] = reset($trueDamagePart);
            } else {
                $specialAssocArray[$part] = '';
            }

            // dump($key,$part);
            if (isset($specialArray[$key + 1]) && substr($specialArray[$key + 1], 0, 1) === '(') {
                $specialAssocArray[$part] = $specialArray[$key + 1];
                if (substr($specialArray[$key + 1], 0, 1) === '(' && substr($specialArray[$key + 1], -1, 1) === ')') {
                    $specialAssocArray[$part] = substr($specialArray[$key + 1], 1,
                        strrpos($specialArray[$key + 1], ')') - 1);
                }
            }
        }

        $specials = new ArrayCollection();
        foreach ($specialAssocArray as $specialName => $specialInnerString) {
            $specialType = $this->documentManager->getRepository(SpecialType::class)->findCaseInsentive('name',
                $specialName);
            if (!$specialType instanceof SpecialType) {
                throw new Exception('This special have not been found, please import it beforehand : ' . $specialName);
            }
            $special = new Special();
            $special->setSpecialType($specialType);
            $special->setValue($specialInnerString);
            if (in_array($specialType->getLabel(), self::SPECIAL_LABEL_THAT_CAN_HAVE_SUB_SPECIALS)) {
                $this->bindOtherSpecialOrConditionFromString($specialInnerString, $special);
            }
            $specials->add($special);
        }

        return $specials;
    }

    public function getSpecialsAsString(ArrayCollection $specials): string
    {
        $asString = '';
        /** @var Special $special */
        foreach ($specials as $special) {
            if ($asString !== '') {
                $asString .= ", ";
            }
            $asString .= $this->getCriticalAsString($special);
        }

        return $asString;
    }

    public static function getSpecialAsString(Special $special): string
    {
        $asString = '';
        $asString .= sprintf("%s %s%s%s",
            $special->getSpecialType()->getLabel(),
            $special->getSpecialType()->getStartDelimiter(),
            $special->getValue(),
            $special->getSpecialType()->getEndDelimiter()
        );
        $asString = trim($asString);

        return $asString;
    }

    private function bindOtherSpecialOrConditionFromString(string $refString, Special $special)
    {
        $refArray = explode(',', $refString);
        foreach ($refArray as $key => $value) {
            $string = trim($value);
            if (strcspn($string, '0123456789') === 0) {
                continue; // if first carac is a Number it can't be a ref.
            }
            $type = trim($string);
            if (strcspn($string, '0123456789') !== strlen($string)) {
                $type = trim(strstr($string, $string[strcspn($string, '0123456789')], true));
            }

            $specialType = $this->documentManager->getRepository(SpecialType::class)->findCaseInsentive('name', $type);
            $conditionType = $this->documentManager->getRepository(ConditionType::class)->findCaseInsentive('name',
                $type);
            if ($specialType) {
                $special->addOtherSubSpecial($specialType);
            } elseif ($conditionType) {
                $special->addOtherSubCondition($conditionType);
            }
        }
    }
}

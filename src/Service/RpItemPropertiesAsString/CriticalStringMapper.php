<?php
/**
 * @author Letonade
 * @date   06/11/2021 19:56
 */

namespace App\Service\RpItemPropertiesAsString;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\ConditionType\ConditionType;
use App\Document\Depository\Properties\Critical\Critical;
use App\Document\Depository\Properties\Critical\CriticalType;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

trait CriticalStringMapper
{
    public function getCriticalsFromString(string $criticalsString, Depository $depository): ?ArrayCollection
    {
        // $split = "/([(].*?[)])|(\w)+/";
        $split = "/([(].*?[)])|([a-zA-Z0-9_ +-])+/";
        preg_match_all($split, $criticalsString, $criticalArray);
        $criticalArray = $criticalArray[0];
        $criticalAssocArray = [];
        foreach ($criticalArray as $key => $part) {
            if (ord($part) === 151 || ord($part) === 45) {
                continue;// When no damage string is a Char(151) like a "-"
            }
            if (substr($part, 0, 1) === '(') {
                continue;
            }
            $part = ucfirst(trim($part));

            $diceSplit = "/((\d+)(\d*)d(\d*)([-+*\/><]?\d*))/";// -> find dice
            $trueDamageSplit = "/(\d+)$/";// -> true damage
            preg_match($diceSplit, $part, $diceyDamagePart);
            $partInWordsArray = explode(' ', $part);
            preg_match($trueDamageSplit, $part, $trueDamagePart);
            if (isset($diceyDamagePart[0])) {
                $part = preg_replace($diceSplit, "", $part);
                $part = trim($part);
                $criticalAssocArray[$part] = $diceyDamagePart[0];
            } elseif ($partInWordsArray[array_key_last($partInWordsArray)] === reset($trueDamagePart)) {
                array_splice($partInWordsArray, -1);
                $criticalAssocArray[implode(" ", $partInWordsArray)] = reset($trueDamagePart);
            } else {
                $criticalAssocArray[$part] = '';
            }

            // dump($key,$part);
            if (isset($criticalArray[$key + 1]) && substr($criticalArray[$key + 1], 0, 1) === '(') {
                $criticalAssocArray[$part] = $criticalArray[$key + 1];
                if (substr($criticalArray[$key + 1], 0, 1) === '(' && substr($criticalArray[$key + 1], -1, 1) === ')') {
                    $criticalAssocArray[$part] = substr($criticalArray[$key + 1], 1,
                        strrpos($criticalArray[$key + 1], ')') - 1);
                }
            }
        }

        $criticals = new ArrayCollection();
        foreach ($criticalAssocArray as $criticalName => $criticalInnerString) {
            // dump($criticalName);
            $criticalType = $this->documentManager->getRepository(CriticalType::class)->findCaseInsentive('name',
                $criticalName);
            if (!$criticalType instanceof CriticalType) {
                throw new Exception('This critical have not been found, please import it beforehand : ' . $criticalName);
            }
            $critical = new Critical();
            $critical->setCriticalType($criticalType);
            $critical->setValue($criticalInnerString);
            if (in_array($criticalType->getLabel(), self::CRITICAL_LABEL_THAT_CAN_HAVE_SUB_SPECIALS)) {
                $this->bindOtherSpecialOrConditionFromString($criticalInnerString, $critical);
            }
            $criticals->add($critical);
        }

        return $criticals;
    }

    public function getCriticalsAsString(ArrayCollection $criticals): string
    {
        $asString = '';
        /** @var Critical $critical */
        foreach ($criticals as $critical) {
            if ($asString !== '') {
                $asString .= ", ";
            }
            $asString .= $this->getCriticalsAsString($critical);
        }

        return $asString;
    }

    public static function getCriticalAsString(Critical $critical): string
    {
        $asString = '';
        $asString .= sprintf("%s %s%s%s",
            $critical->getCriticalType()->getLabel(),
            $critical->getCriticalType()->getStartDelimiter(),
            $critical->getValue(),
            $critical->getCriticalType()->getEndDelimiter()
        );
        $asString = trim($asString);

        return $asString;
    }

    private function bindOtherCriticalOrConditionFromString(string $refString, Critical $critical)
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

            $criticalType = $this->documentManager->getRepository(CriticalType::class)->findCaseInsentive('name',
                $type);
            $conditionType = $this->documentManager->getRepository(ConditionType::class)->findCaseInsentive('name',
                $type);
            if ($criticalType) {
                $critical->addOtherSubCritical($criticalType);
            } elseif ($conditionType) {
                $critical->addOtherSubCondition($conditionType);
            }
        }
    }
}

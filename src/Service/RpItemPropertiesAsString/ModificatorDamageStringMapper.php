<?php
/**
 * @author Letonade
 * @date   06/11/2021 19:56
 */

namespace App\Service\RpItemPropertiesAsString;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\DamageType\DamageType;
use App\Document\Common\Damage\ModificatorDamage;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\String\UnicodeString;

trait ModificatorDamageStringMapper
{

    //ex:  12d6 P or A, for test we use Te & St
    public function getModificatorDamagesFromString(string $damageString, Depository $depository): ArrayCollection
    {
        $resultArray = new ArrayCollection();
        $stringArray = preg_split("/or/i", $damageString);
        foreach ($stringArray as $string) {
            if (ord($string[0]) === 151){
                continue;// When no damage string is a Char(151) like a "-"
            }
            $damageTypesFound = $this->findModificatorDamageTypesFromString(preg_replace('/\s+/', '', $string),
                $depository);
            if ($damageTypesFound && !$resultArray->isEmpty() && $resultArray->last() instanceof ModificatorDamage
            ) {
                $damageComposedType = '';
                foreach ($damageTypesFound as $dam) {
                    if ($damageComposedType !== '') {
                        $damageComposedType .= ' & ';
                    }
                    $damageComposedType .= $dam->getAcronym();
                }
                $resultArray->add($this->getModificatorDamageFromString($this->getModificatorDamageAsString($resultArray->last(),
                        false) . " " . $damageComposedType, $depository));
                continue;
            }
            $resultArray->add($this->getModificatorDamageFromString($string, $depository));
        }

        return $resultArray;
    }

    //ex:  12d6 P & F & S, for test we use Te & St
    public function getModificatorDamageFromString(string $damageString, Depository $depository): ModificatorDamage
    {
        $damageObj = new ModificatorDamage();

        $damageString = preg_replace('/\s+/', '', $damageString);
        $damageUnicodeString = new UnicodeString($damageString);
        $damageUnicodeString->ignoreCase(true);

        if ($damageUnicodeString->truncate(1)->toString() === '+') {
            $damageUnicodeString = $damageUnicodeString->after('+');
            $damageObj->setIsAdditionalDamage(true);
        } elseif ($damageUnicodeString->truncate(1)->toString() === '-') {
            $damageUnicodeString = $damageUnicodeString->after('-');
            $damageObj->setIsAdditionalDamage(false);
        } else {
            throw new Exception('Need a sign for modificator damage. :'.$damageString);
        }

        if (!isset($damageUnicodeString->after('d')->truncate(1)->match("/^[0-9]+/")[0])) {
            throw new Exception('Invalid damage string.');
        }
        $damageObj->setDamageDiceAmount((int)$damageUnicodeString->before('d')->toString());
        $damageUnicodeString = $damageUnicodeString->after('d');
        if (!isset($damageUnicodeString->match("/^[0-9]+/")[0])) {
            $damageObj->setDamageDiceFaceCount(0);
        } else {
            $damageObj->setDamageDiceFaceCount($damageUnicodeString->match("/^[0-9]+/")[0]);
            $damageUnicodeString = $damageUnicodeString->after($damageUnicodeString->match("/^[0-9]+/")[0]);
        }
        if ($damageUnicodeString->truncate(1)->toString() === '-') {
            $damageUnicodeString = $damageUnicodeString->after('-');
            $damageObj->setDamageFixedAmount(-$damageUnicodeString->match("/^[0-9]+/")[0]);
            $damageUnicodeString = $damageUnicodeString->after($damageUnicodeString->match("/^[0-9]+/")[0]);
        } elseif ($damageUnicodeString->truncate(1)->toString() === '+') {
            $damageUnicodeString = $damageUnicodeString->after('+');
            $damageObj->setDamageFixedAmount(+$damageUnicodeString->match("/^[0-9]+/")[0]);
            $damageUnicodeString = $damageUnicodeString->after($damageUnicodeString->match("/^[0-9]+/")[0]);
        } else {
            $damageObj->setDamageFixedAmount(0);
        }

        if ($damageObj->getDamageFixedAmount() === 0 && ($damageObj->getDamageDiceFaceCount() === 0 || $damageObj->getDamageDiceAmount() === 0)) {
            throw new Exception('No damage found.');
        }

        $damageObj->setDamageTypes($this->findModificatorDamageTypesFromString($damageUnicodeString->toString(),
            $depository));

        if (!$damageObj || $damageObj->getDamageTypes()->isEmpty()) {
            throw new Exception('No corresponding damages type found.');
        }

        return $damageObj;
    }

    public static function getModificatorDamageAsString(ModificatorDamage $modificatorDamage, bool $withType = true): string
    {
        $sign = "+";
        if (!$modificatorDamage->isAdditionalDamage()) {
            $sign = "-";
        }

        $fixedDamage = $modificatorDamage->getDamageFixedAmount();
        if ($modificatorDamage->getDamageFixedAmount() > 0) {
            $fixedDamage = '+' . $modificatorDamage->getDamageFixedAmount();
        } elseif ($modificatorDamage->getDamageFixedAmount() === 0) {
            $fixedDamage = '';
        }
        $dices = sprintf('%dd%d',
            $modificatorDamage->getDamageDiceAmount(),
            $modificatorDamage->getDamageDiceFaceCount());
        if ($modificatorDamage->getDamageDiceAmount() === 0 || $modificatorDamage->getDamageDiceFaceCount() <= 0) {
            $dices = '';
        }
        if (!$dices && !$fixedDamage) {
            $dices = 0;
        }

        $damageComposedType = '';
        foreach ($modificatorDamage->getDamageTypes() as $dam) {
            if ($damageComposedType !== '') {
                $damageComposedType .= ' & ';
            }
            $damageComposedType .= $dam->getAcronym();
        }

        if (!$modificatorDamage->getDamageTypes() || $damageComposedType === '') {
            throw new Exception('Please add a damage type.');
        }

        if (!$withType) {
            return sprintf('%s%s%s',
                $sign,
                $dices,
                $fixedDamage
            );
        }

        return sprintf('%s%s%s %s',
            $sign,
            $dices,
            $fixedDamage,
            $damageComposedType
        );
    }

    private function findModificatorDamageTypesFromString(string $damageString, Depository $depository): ?ArrayCollection
    {
        $arrayColl = new ArrayCollection();

        if (!$damageString || ord($damageString[0]) === 151){
            $damageString = '-';
        }

        $damageUnicodeString = new UnicodeString($damageString);
        $damageUnicodeString->ignoreCase(true);
        $damageTypeFromStringArray = $damageUnicodeString->split('&');

        foreach ($damageTypeFromStringArray as $damageTypeFromString) {
            $damageTypeFounder = $this->documentManager->getRepository(DamageType::class)->findDamageTypeByAcronym($damageTypeFromString->toString(),
                $depository);
            if (!$damageTypeFounder instanceof DamageType) {
                return null;
                // throw new Exception('No corresponding damage type found.');
            }
            $arrayColl->add($damageTypeFounder);
        }

        return $arrayColl;
    }
}

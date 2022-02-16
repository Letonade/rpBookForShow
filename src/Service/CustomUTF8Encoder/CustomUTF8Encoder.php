<?php
/**
 * @author Letonade
 * @date   06/11/2021 19:56
 */

namespace App\Service\CustomUTF8Encoder;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\CapacityType\CapacityAndUsage;
use App\Document\Depository\Properties\CapacityType\CapacityType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\String\UnicodeString;
use Exception;

class CustomUTF8Encoder
{
    public static function cusUTF8Encode(string $str): string
    {
        $StrArr = str_split($str); $NewStr = '';
        foreach ($StrArr as $Char) {
            $CharNo = ord($Char);
            if ($CharNo == 151) { $NewStr .= '-'; continue; }
            if ($CharNo == 150) { $NewStr .= '-'; continue; }
            if ($CharNo == 146) { $NewStr .= '`'; continue; }
            if ($CharNo == 147) { $NewStr .= '*'; continue; }
            if ($CharNo == 148) { $NewStr .= '*'; continue; }
            if ($CharNo == 176) { $NewStr .= ' degree'; continue; }
            if ($CharNo == 215) { $NewStr .= 'x'; continue; }
            if ($CharNo == 10 ) { $NewStr .= $Char; continue; }
            if ($CharNo == 0 ) { $NewStr .= $Char; continue; }
            if ($CharNo > 31 && $CharNo < 127) {$NewStr .= $Char; continue; }
            dump("Please implements in cusUTF8Encoder:".$CharNo." => '".$Char."'");
        }
        return $NewStr;
    }
}

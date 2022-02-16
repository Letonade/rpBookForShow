<?php
/**
 * @author Letonade
 * @date   19/11/2021 18:32
 */

namespace App\Command\Import;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Command\Command;
use Exception;
use League\Csv\Reader;

abstract class CSVImportHelper extends Command
{
    protected const FILE_PUBLIC_STORAGE = './public/ImportCSV';

    protected function readCSVIntoArray(string $accessPath): ArrayCollection
    {
        $collection = new ArrayCollection();
        if (!ini_get("auto_detect_line_endings")) {
            ini_set("auto_detect_line_endings", '1');
        }
        $csv = Reader::createFromPath(self::FILE_PUBLIC_STORAGE. $accessPath, 'r');
        $csv->setDelimiter(";");
        $records = $csv->getRecords();
        foreach ($records as $key => $record){
            if ($key === 0 || ($record[0] === '')){
                continue;
            }
            $collection->add($record);
        }

        if ($collection->isEmpty()){
            throw new Exception("No data or file found");
        }

        return $collection;
    }
}

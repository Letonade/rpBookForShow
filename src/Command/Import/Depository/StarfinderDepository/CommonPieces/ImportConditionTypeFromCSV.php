<?php

declare(strict_types=1);

namespace App\Command\Import\Depository\StarfinderDepository\CommonPieces;

use App\Command\Import\CSVImportHelper;
use App\Document\Depository\Depository;
use App\Document\Depository\Properties\ConditionType\ConditionType;
use App\Document\Depository\RpItem\Weapon\asicMeleeWeaponRpItem;
use App\Document\Depository\Properties\CapacityType\CapacityType;
use App\Document\Depository\Properties\Critical\CriticalType;
use App\Document\Depository\Properties\Special\SpecialType;
use App\Document\Depository\Properties\WeaponCategory\WeaponCategory;
use App\Document\Book\Book;
use App\Document\Common\Assess\Assess;
use App\Document\Order\Order;
use App\Service\CustomUTF8Encoder\CustomUTF8Encoder;
use App\Service\Plugin\Como\ComoService;
use App\Service\RpItemPropertiesAsString\RpItemPropertiesAsStringMapper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportConditionTypeFromCSV extends CSVImportHelper
{
    const FILE_ACCESS_PATH = '/Depository/Starfinder/CommonPieces/ConditionType.csv';
    protected static $defaultName = 'app:import:add:sf:common:condition-type';

    /** @var DocumentManager */
    private $documentManager;
    /** @var ManagerRegistry */
    private $managerRegistry;
    /** @var LoggerInterface */
    private $importsLogger;
    /** @var ArrayCollection */
    private $specColl;
    /** @var Depository */
    private $depository;
    /** @var RpItemPropertiesAsStringMapper */
    private $stringMapper;

    public function __construct(
        DocumentManager $documentManager,
        ManagerRegistry $managerRegistry,
        LoggerInterface $importsLogger,
        RpItemPropertiesAsStringMapper $stringMapper,
        string $name = null
    ) {
        parent::__construct($name);
        $this->documentManager = $documentManager;
        $this->managerRegistry = $managerRegistry;
        $this->importsLogger = $importsLogger;
        $this->stringMapper = $stringMapper;
    }

    protected function configure(): void
    {
        $this
            ->addOption('depositoryName', '-a', InputOption::VALUE_REQUIRED, 'The depository name default= "Starfinder Depository"')
            ->addOption('csvPath', '-p', InputOption::VALUE_REQUIRED, 'The path of the .csv to use.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $depositoryName = $input->getOption('depositoryName');
        $csvPath = $input->getOption('csvPath');
        if (!$depositoryName){ $depositoryName = 'Starfinder Depository'; }
        if (!$csvPath){ $csvPath = self::FILE_ACCESS_PATH; }
        $this->importsLogger->info('Starting ' . self::$defaultName . ' command.');

        $this->depository = $this->documentManager->getRepository(Depository::class)->findOneBy(['name' => $depositoryName]);
        if (!$this->depository instanceof Depository) {
            throw new Exception($depositoryName." not found.");
        }

        $this->specColl = $this->readCSVIntoArray(self::FILE_ACCESS_PATH);

        $count = 0;
        foreach ($this->specColl as $row) {
            $count++;
            $conditionType = $this->composeFromRow($row);
            $conditionTypeFinder = $this->documentManager->getRepository(ConditionType::class)->findOneBy(['name' => $conditionType->getName()]);
            if ($conditionTypeFinder){continue;}
            $this->documentManager->persist($conditionType);
            $this->importsLogger->info('[' . $count . ']Persisted condition-type: ' . $conditionType->getName());
        }
        $this->documentManager->flush();
        $this->importsLogger->info('Flushed ! Now starting sub association...');

        $count = 0;
        $this->specColl = $this->readCSVIntoArray(self::FILE_ACCESS_PATH);
        foreach ($this->specColl as $row) {
            $count++;
            $conditionTypeFinder = $this->documentManager->getRepository(ConditionType::class)->findOneBy(['name' => $row[0]]);
            if (!$conditionTypeFinder){throw new \Exception('Aborting sub association, "'. $row[0].'" must exist and just have been added but is not found.');}
            $this->subAssociateCondition($row, $conditionTypeFinder);
            $this->documentManager->persist($conditionType);
            $this->importsLogger->info('[' . $count . ']Persisted sub associated condition-type: '.$conditionTypeFinder->getName());
        }
        $this->documentManager->flush();
        $this->importsLogger->info('Flushed !');
        $this->importsLogger->info('End of the command ' . self::$defaultName);

        return 1;
    }

    private function composeFromRow(array $row)
    {
        $conditionType = new ConditionType();
        $conditionType->setDepository($this->depository);
        $conditionType->setName($row[0]);
        $conditionType->setTitle($row[1]);
        $conditionType->setSpecification(CustomUTF8Encoder::cusUTF8Encode($row[2]));
        $conditionType->setShortDescription(CustomUTF8Encoder::cusUTF8Encode($row[3]));
        $conditionType->setDescription(CustomUTF8Encoder::cusUTF8Encode($row[4]));

        return $conditionType;
    }

    private function subAssociateCondition(array $row, ConditionType $cond)
    {
        if (isset($row[5]))
        $string = $row[5];
        $strArr = explode(',',$string);
        foreach ($strArr as $key => $val){
            $val = trim($val);
            if (!$val){continue;}
            $sub = $this->documentManager->getRepository(ConditionType::class)->findOneBy(['name' => $val]);
            if (!$sub){$this->importsLogger->warning('Not found:'.$val);continue;}
            $cond->addSubCondition($sub);
            $this->importsLogger->info('Added sub:'.$val.' - to :'.$cond->getName());
        }
    }
}

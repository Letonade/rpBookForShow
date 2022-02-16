<?php

declare(strict_types=1);

namespace App\Command\Import\Depository\StarfinderDepository\CommonPieces;

use App\Command\Import\CSVImportHelper;
use App\Document\Depository\Depository;
use App\Document\Depository\Properties\WeaponCategory\WeaponCategory;
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

class ImportWeaponCategoryFromCSV extends CSVImportHelper
{
    const FILE_ACCESS_PATH = '/Depository/Starfinder/CommonPieces/WeaponCategory.csv';
    protected static $defaultName = 'app:import:add:sf:common:weapon-category';

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
            $weaponCategory = $this->composeFromRow($row);
            $categoryFinder = $this->documentManager->getRepository(WeaponCategory::class)->findOneBy(['name' => $weaponCategory->getName()]);
            if ($categoryFinder) {
                continue;
            }
            $this->documentManager->persist($weaponCategory);
            $this->importsLogger->info('[' . $count . ']Persisted special: ' . $weaponCategory->getName());
        }
        $this->documentManager->flush();

        // $this->testsLogger->info('Added '.$depository->getName().'('.$depository->getId().').');
        $this->importsLogger->info('Flushed ! ');
        $this->importsLogger->info('End of the command ' . self::$defaultName);

        return 1;
    }

    private function composeFromRow(array $row)
    {

        $weaponCategory = new WeaponCategory();
        $weaponCategory->setDepository($this->depository);
        $weaponCategory->setName($row[0]);
        $weaponCategory->setDescription(CustomUTF8Encoder::cusUTF8Encode($row[1]));

        return $weaponCategory;
    }
}

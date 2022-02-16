<?php

declare(strict_types=1);

namespace App\Command\Import\Depository\StarfinderDepository\CRAR;

use App\Command\Import\CSVImportHelper;
use App\Document\Book\Book;
use App\Document\Depository\Depository;
use App\Document\Depository\Properties\ArmorType\ArmorType;
use App\Document\Depository\Properties\FamilyDescription\FamilyDescription;
use App\Document\Depository\Properties\TechType\TechType;
use App\Document\Depository\RpItem\ArmorUpgrade\ArmorUpgradeRpItem;
use App\Document\Depository\RpItem\Weapon\AdvancedMeleeWeaponRpItem;
use App\Document\Order\Order;
use App\Service\CustomUTF8Encoder\CustomUTF8Encoder;
use App\Service\Plugin\Como\ComoService;
use App\Service\RpItemPropertiesAsString\RpItemPropertiesAsStringMapper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportArmorUpgradeFromCSV extends CSVImportHelper
{
    const FILE_ACCESS_PATH = '/Depository/Starfinder/CRAR/ArmorUpgrade.csv';
    protected static $defaultName = 'app:import:add:sf:crar:armor-upgrade';

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
            ->addOption('depositoryName', '-a', InputOption::VALUE_REQUIRED,
                'The depository name default= "Starfinder Depository"')
            ->addOption('csvPath', '-p', InputOption::VALUE_REQUIRED, 'The path of the .csv to use.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $depositoryName = $input->getOption('depositoryName');
        $csvPath = $input->getOption('csvPath');
        if (!$depositoryName) {
            $depositoryName = 'Starfinder Depository';
        }
        if (!$csvPath) {
            $csvPath = self::FILE_ACCESS_PATH;
        }
        $this->importsLogger->info('Starting ' . self::$defaultName . ' command.');

        $this->depository = $this->documentManager->getRepository(Depository::class)->findOneBy(['name' => $depositoryName]);
        if (!$this->depository instanceof Depository) {
            throw new Exception($depositoryName . " not found.");
        }

        $this->specColl = $this->readCSVIntoArray(self::FILE_ACCESS_PATH);

        $count = 0;
        foreach ($this->specColl as $row) {
            $count++;
            $item = $this->composeFromRow($row);
            $itemFinder = $this->documentManager->getRepository(ArmorUpgradeRpItem::class)->findOneBy(['name' => $item->getName()]);
            if ($itemFinder) {
                continue;
            }
            $this->documentManager->persist($item);
            $this->importsLogger->info('[' . $count . ']Persisted armor upgrade : ' . $item->getName());
        }
        $this->documentManager->flush();

        $this->importsLogger->info('Flushed ! ');
        $this->importsLogger->info('End of the command ' . self::$defaultName);

        return 1;
    }

    private function composeFromRow(array $row)
    {
        $book = $this->documentManager->getRepository(Book::class)->findOneBy(['acronym' => $row[0]]);
        if (!$book instanceof Book) {
            throw new Exception("Book not found." . $row[0]);
        }

        $item = new ArmorUpgradeRpItem();
        $item->setDepository($this->depository);
        $item->setSourceBook($book);
        $item->setSourcePage($row[1]);
        $item->setName(CustomUTF8Encoder::cusUTF8Encode($row[2]));

        if ($row[3] === '-') {
            $item->setLevel(0);
        } else {
            $item->setLevel((int)CustomUTF8Encoder::cusUTF8Encode($row[3]));
        }
        $item->setCost((int)CustomUTF8Encoder::cusUTF8Encode($row[4]), "credits");
        $item->setNumberOfUpgradeSlot((int)CustomUTF8Encoder::cusUTF8Encode($row[5]));

        $armorTypes = explode(', ', strtolower(CustomUTF8Encoder::cusUTF8Encode($row[6])));
        foreach ($armorTypes as $armorTypeString) {
            $armorTypeString = trim($armorTypeString);
            if ($armorTypeString === 'any') {
                $anyArmor = ['heavy', 'powered', 'light'];
                $dbArmor = $this->documentManager->createQueryBuilder(ArmorType::class)->field('name')->in($anyArmor)->getQuery()->execute();
                foreach ($dbArmor as $armorType) {
                    if (!$armorType instanceof ArmorType) {
                        $mess = 'armorType not found :' . $armorTypeString;
                        $this->importsLogger->error($mess);
                        throw new Exception($mess);
                    }
                    $item->addArmorType($armorType);
                }
            } else {
                $armorType = $this->documentManager->getRepository(ArmorType::class)->findOneBy(['name' => $armorTypeString]);
                if (!$armorType instanceof ArmorType) {
                    $mess = 'armorType not found :' . $armorTypeString;
                    $this->importsLogger->error($mess);
                    throw new Exception($mess);
                }
                $item->addArmorType($armorType);
            }
        }

        $item->setBulk(CustomUTF8Encoder::cusUTF8Encode($row[7]));

        if ($row[8]) {
            $item->setCapacityAndUsages($this->stringMapper->getCapacitiesAndUsagesFromString($row[8], $row[9],
                $this->depository, $row[10]));
        }

        $techTypeString = trim(strtolower(CustomUTF8Encoder::cusUTF8Encode($row[11])));
        $techType = $this->documentManager->getRepository(TechType::class)->findOneBy(['name' => $techTypeString]);
        if (!$techType instanceof TechType){ throw new Exception('Tech type not found'); }
        $item->setTechType($techType);

        $famDesc = $this->documentManager->getRepository(FamilyDescription::class)->findOneBy([
            'slug' => $row[12]
        ]);
        if (!$famDesc instanceof FamilyDescription){throw new Exception('FamilyDescription not found with slug:'.$row[12]);}
        $item->setFamilyDescription($famDesc);

        return $item;
    }
}

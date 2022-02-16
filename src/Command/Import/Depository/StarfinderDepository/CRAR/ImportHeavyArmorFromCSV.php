<?php

declare(strict_types=1);

namespace App\Command\Import\Depository\StarfinderDepository\CRAR;

use App\Command\Import\CSVImportHelper;
use App\Document\Depository\Depository;
use App\Document\Depository\Properties\FamilyDescription\FamilyDescription;
use App\Document\Depository\Properties\TechType\TechType;
use App\Document\Depository\RpItem\Armor\HeavyArmorRpItem;
use App\Document\Depository\RpItem\Armor\LightArmorRpItem;
use App\Document\Depository\RpItem\Weapon\AdvancedMeleeWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\HeavyWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\LongarmRpItem;
use App\Document\Depository\Properties\WeaponCategory\WeaponCategory;
use App\Document\Depository\RpItem\Weapon\SmallArmRpItem;
use App\Document\Depository\RpItem\Weapon\SniperWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\SpecialWeaponRpItem;
use App\Document\Depository\RpItem\Fusion\WeaponFusionRpItem;
use App\Document\Book\Book;
use App\Document\Common\Range\Range;
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
use Symfony\Component\String\UnicodeString;

class ImportHeavyArmorFromCSV extends CSVImportHelper
{
    const FILE_ACCESS_PATH = '/Depository/Starfinder/CRAR/HeavyArmor.csv';
    protected static $defaultName = 'app:import:add:sf:crar:heavy-armor';

    /** @var DocumentManager */
    private $documentManager;
    /** @var ManagerRegistry */
    private $managerRegistry;
    /** @var LoggerInterface */
    private $importsLogger;
    /** @var ArrayCollection */
    private $saColl;
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

        $this->saColl = $this->readCSVIntoArray($csvPath);

        $count = 0;
        foreach ($this->saColl as $row) {
            $count++;
            $heavyArmor = $this->composeFromRow($row);
            $this->documentManager->persist($heavyArmor);
            $this->importsLogger->info('[' . $count . ']Persisted heavy armor : ' . $heavyArmor->getName());
        }
        $this->documentManager->flush();
        $this->importsLogger->info('Flushed !');
        $this->importsLogger->info('End of the command ' . self::$defaultName);

        return 1;
    }

    private function composeFromRow(array $row)
    {
        $book = $this->documentManager->getRepository(Book::class)->findOneBy(['acronym' => $row[0]]);
        if (!$book instanceof Book) {
            throw new Exception("Book not found." . $row[0]);
        }

        $item = new HeavyArmorRpItem();
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
        if ($row[5] === '—'){$row[5] = 0;}
        $item->setEnergeticArmorClassModificator((int)CustomUTF8Encoder::cusUTF8Encode($row[5]));
        if ($row[6] === '—'){$row[6] = 0;}
        $item->setKineticArmorClassModificator((int)CustomUTF8Encoder::cusUTF8Encode($row[6]));
        if ($row[7] === '—'){$row[7] = 0;}
        $item->setMaximumDexterityModificator((int)CustomUTF8Encoder::cusUTF8Encode($row[7]));
        if ($row[8] === '—'){$row[8] = 0;}
        $item->setArmorCheckModificator((int)CustomUTF8Encoder::cusUTF8Encode($row[8]));
        if ($row[9] === '—'){$row[9] = 0;}
        $speed = new Range();
        $speed->setValueInFeet((int)CustomUTF8Encoder::cusUTF8Encode($row[9]));
        $item->setSpeedModificator($speed);
        if (ord($row[10]) === 151){$row[10] = '-';}
        $item->setNumberOfUpgradeSlot((int)CustomUTF8Encoder::cusUTF8Encode($row[10]));

        if (ord($row[11]) === 151){$row[11] = '-';}
        $item->setBulk(CustomUTF8Encoder::cusUTF8Encode($row[11]));

        $techTypeString = trim(strtolower(CustomUTF8Encoder::cusUTF8Encode($row[12])));
        $techType = $this->documentManager->getRepository(TechType::class)->findOneBy(['name' => $techTypeString]);
        if (!$techType instanceof TechType){ throw new Exception('Tech type not found'); }
        $item->setTechType($techType);

        $famDesc = $this->documentManager->getRepository(FamilyDescription::class)->findOneBy([
            'slug' => CustomUTF8Encoder::cusUTF8Encode($row[13])
        ]);

        if (!$famDesc instanceof FamilyDescription){throw new Exception('FamilyDescription not found with slug:'.$row[13]);}
        $item->setFamilyDescription($famDesc);

        return $item;
    }
}

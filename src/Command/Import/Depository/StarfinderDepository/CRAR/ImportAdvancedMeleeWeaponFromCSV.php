<?php

declare(strict_types=1);

namespace App\Command\Import\Depository\StarfinderDepository\CRAR;

use App\Command\Import\CSVImportHelper;
use App\Document\Depository\Depository;
use App\Document\Depository\Properties\FamilyDescription\FamilyDescription;
use App\Document\Depository\Properties\TechType\TechType;
use App\Document\Depository\RpItem\Weapon\AdvancedMeleeWeaponRpItem;
use App\Document\Depository\Properties\WeaponCategory\WeaponCategory;
use App\Document\Book\Book;
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

class ImportAdvancedMeleeWeaponFromCSV extends CSVImportHelper
{
    const FILE_ACCESS_PATH = '/Depository/Starfinder/CRAR/AdvancedMeleeWeapon.csv';
    protected static $defaultName = 'app:import:add:sf:crar:amw';

    /** @var DocumentManager */
    private $documentManager;
    /** @var ManagerRegistry */
    private $managerRegistry;
    /** @var LoggerInterface */
    private $importsLogger;
    /** @var ArrayCollection */
    private $amwColl;
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

        $this->amwColl = $this->readCSVIntoArray($csvPath);

        $count = 0;
        foreach ($this->amwColl as $row) {
            $count++;
            $amw = $this->composeFromRow($row);
            $this->documentManager->persist($amw);
            $this->importsLogger->info('[' . $count . ']Persisted amw: ' . $amw->getName());
        }
        $this->documentManager->flush();
        // $this->testsLogger->info('Added '.$depository->getName().'('.$depository->getId().').');
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

        $item = new AdvancedMeleeWeaponRpItem();
        $item->setDepository($this->depository);
        $item->setSourceBook($book);
        $item->setSourcePage($row[1]);
        $item->setNumberOfHandNeeded((int)CustomUTF8Encoder::cusUTF8Encode($row[2]));

        // $weaponCategory = $this->documentManager->getRepository(WeaponCategory::class)->findOneBy(['name' => $row[3]]);
        $weaponCategory = $this->documentManager->getRepository(WeaponCategory::class)->findCaseInsentive('name',
            $row[3]);
        // $weaponCategory = $this->documentManager->createQueryBuilder()->
        if (!$weaponCategory instanceof WeaponCategory) {
            throw new Exception("Weapon Category not found." . $row[3]);
        }
        // if (!$weaponCategory instanceof WeaponCategory){
        //     $amw->setWeaponCategory($this->createWeaponCategoryFromName($row[3]));
        // }else {
        $item->setWeaponCategory($weaponCategory);
        // }
        //
        $item->setName($row[4]);

        if ($row[5] === '-') {
            $item->setLevel(0);
        } else {
            $item->setLevel((int)CustomUTF8Encoder::cusUTF8Encode($row[5]));
        }

        $row[6] = str_replace(',', '', $row[6]);
        $item->setCost((int)CustomUTF8Encoder::cusUTF8Encode($row[6]), "credits");

        $item->setDamages($this->stringMapper->getDamagesFromString($row[7], $this->depository));

        $item->setCriticals($this->stringMapper->getCriticalsFromString($row[8], $this->depository));

        if (ord($row[9]) === 151) {
            $row[9] = '-';
        }
        $item->setBulk(CustomUTF8Encoder::cusUTF8Encode($row[9]));

        $item->setSpecials($this->stringMapper->getSpecialsFromString($row[10], $this->depository));

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

    private function createWeaponCategoryFromName(string $weaponCategoryName)
    {
        $wc = new WeaponCategory();
        $wc->setDepository($this->depository);
        $wc->setDescription('');
        $wc->setName($weaponCategoryName);

        return $wc;
    }
}

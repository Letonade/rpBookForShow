<?php

declare(strict_types=1);

namespace App\Command\Test\Depository;

use App\Document\Depository\Depository;
use App\Document\Depository\RpItem\AugmentRpItem;
use App\Document\Depository\RpItem\BattleWare\AmmunitionRpItem;
use App\Document\Depository\RpItem\BattleWare\GrenadeRpItem;
use App\Document\Depository\Properties\CapacityType\CapacityAndUsage;
use App\Document\Depository\Properties\CapacityType\CapacityType;
use App\Document\Depository\Properties\Critical\Critical;
use App\Document\Depository\Properties\Critical\CriticalType;
use App\Document\Depository\Properties\DamageType\DamageType;
use App\Document\Depository\Properties\FamilyDescription\FamilyDescription;
use App\Document\Depository\Properties\Special\Special;
use App\Document\Depository\Properties\Special\SpecialType;
use App\Document\Depository\Properties\WeaponCategory\WeaponCategory;
use App\Document\Depository\RpItem\Weapon\AdvancedMeleeWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\BasicMeleeWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\HeavyWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\LongarmRpItem;
use App\Document\Depository\RpItem\Weapon\SmallArmRpItem;
use App\Document\Depository\RpItem\Weapon\SniperWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\SolarianWeaponCrystalRpItem;
use App\Document\Depository\RpItem\Weapon\SpecialWeaponRpItem;
use App\Document\Book\Book;
use App\Document\Common\Customfield\Customfield;
use App\Document\Common\Extrafield\Extrafield;
use App\Document\Order\Order;
use App\Service\Plugin\Como\ComoService;
use App\Service\RpItemPropertiesAsString\DamageTypeStringMapper;
use App\Service\RpItemPropertiesAsString\RpItemPropertiesAsStringMapper;
use App\Service\TestNaming\TestNamingService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestAddOneItemOfEach extends Command
{
    protected static $defaultName = 'app:test:add:items';

    private $depository;
    private $book;
    private $specialDamage;
    private $specialExplosion;
    private $critical;
    private $weaponCategory;
    private $damageType;
    private $customField;
    private $extraField;
    private $familyDescription;
    private $ammunitionType;


    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var LoggerInterface
     */
    private $testsLogger;
    /**
     * @var RpItemPropertiesAsStringMapper
     */
    private $stringMapper;

    public function __construct(
        DocumentManager $documentManager,
        ManagerRegistry $managerRegistry,
        LoggerInterface $testsLogger,
        RpItemPropertiesAsStringMapper $stringMapper,
        string $name = null
    ) {
        parent::__construct($name);
        $this->documentManager = $documentManager;
        $this->managerRegistry = $managerRegistry;
        $this->testsLogger = $testsLogger;
        $this->stringMapper = $stringMapper;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->testsLogger->info('Starting ' . self::$defaultName . ' command.');
        $this->initializeTestVars();

        $serviceTested = new RpItemPropertiesAsStringMapper($this->documentManager);
        // dd($serviceTested->getCriticalsFromString("Block, powered (capacity 40, usage 2), thrown (20 ft.)", $this->depository));
        // dd($serviceTested->getCriticalsFromString("Block, powered (capacity 40, ", $this->depository));
        // dd($serviceTested->getCriticalsFromString("Block", $this->depository));
        // dd($serviceTested->getModificatorDamageAsString($serviceTested->getModificatorDamageFromString('-8d1+3 Te&ST', $this->depository)));
        // dump($serviceTested->getModificatorDamageAsString($serviceTested->getModificatorDamagesFromString('-8d1+3 Te or Te&St', $this->depository)[0]));
        // dd($serviceTested->getModificatorDamageAsString($serviceTested->getModificatorDamageFromString('-8d1+3 te Or te  &ST', $this->depository)[1]));
        // dd($serviceTested->getRpItemSpeedMovementsAsString($serviceTested->getRpItemSpeedMovementsFromString('fly 20, climb 80 ffffff, 22 ', $this->depository)));

        $this->customField = new Customfield();
        $this->customField->setPublic(true);
        $this->customField->setValue('test');
        $this->customField->setHandle('CF-TEST');

        $this->extraField = new Extrafield();
        $this->extraField->setPublic(true);
        $this->extraField->setValue('test');
        $this->extraField->setHandle('EF-TEST');

        $this->addAmmunition();
        $this->addAugments();
        $this->addGrenades();
        $this->addBasicMeleeWeapon();
        $this->addAdvancedMeleeWeapon();
        $this->addSmallArm();
        $this->addLongarm();
        $this->addHeavyWeapon();
        $this->addSniperWeapon();
        $this->addSpecialWeapon();
        $this->addSolarianCrystalWeapon();

        return 1;
    }

    public function addAmmunition()
    {
        $ammo = new AmmunitionRpItem();
        $ammo->setName(TestNamingService::TEST_AMMUNITION_NAME);
        $ammo->setAvailable(true);
        $ammo->setDepository($this->depository);
        $ammo->setIsStandard(true);
        $ammo->setLevel(0);
        $ammo->setBulk('L');
        $ammo->setSourceBook($this->book);
        $ammo->setSourcePage('testP');
        $ammo->setCost(120, "credit");

        $ammoSpecial = new Special();
        $ammoSpecial->setSpecialType($this->specialDamage);
        $ammo->addSpecial($ammoSpecial);

        $ammo->addExtrafield($this->extraField);
        $ammo->addCustomfield($this->customField);
        $this->documentManager->persist($ammo);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $ammo->getName() . '(' . $ammo->getId() . ').');
    }

    public function addAugments()
    {
        $augmentation = new AugmentRpItem();
        $augmentation->setName(TestNamingService::TEST_AUGMENT_ITEM_NAME);
        $augmentation->setAvailable(true);
        $augmentation->setDepository($this->depository);
        $augmentation->setLevel(0);
        $augmentation->setSourceBook($this->book);
        $augmentation->setSourcePage('testP');
        $augmentation->setCost(2000, "credit");
        $augmentation->setSystem('Head');

        $augmentation->addExtrafield($this->extraField);
        $augmentation->addCustomfield($this->customField);
        $this->documentManager->persist($augmentation);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $augmentation->getName() . '(' . $augmentation->getId() . ').');
    }

    public function addGrenades()
    {

        $grenade = new GrenadeRpItem();
        $grenade->setName(TestNamingService::TEST_GRENADE_ITEM_NAME);
        $grenade->setAvailable(true);
        $grenade->setDepository($this->depository);
        $grenade->setLevel(0);
        $grenade->setSourceBook($this->book);
        $grenade->setSourcePage('testP');
        $grenade->setCost(200, "credit");

        $special = new Special();
        $special->setSpecialType($this->specialExplosion);
        $special->setValue('1d8');
        $grenade->addSpecial($special);

        $grenade->addExtrafield($this->extraField);
        $grenade->addCustomfield($this->customField);
        $this->documentManager->persist($grenade);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $grenade->getName() . '(' . $grenade->getId() . ').');
    }

    public function addBasicMeleeWeapon()
    {

        $bmw = new BasicMeleeWeaponRpItem();
        $bmw->setName(TestNamingService::TEST_BASIC_MELEE_WEAPON_ITEM_NAME);
        $bmw->setAvailable(true);
        $bmw->setDepository($this->depository);
        $bmw->setLevel(0);
        $bmw->setSourceBook($this->book);
        $bmw->setSourcePage('testP');
        $bmw->setCost(900, "credit");
        // $bmw->setDamageType($this->damageType);
        // $bmw->setDamageDiceAmount(2);
        // $bmw->setDamageDiceFaceCount(4);
        // $bmw->setDamageFixedAmount(-1);
        $bmw->setDamages($this->stringMapper->getDamagesFromString('1d4 +3 Teorst', $this->depository));

        $critical = new Critical();
        $critical->setCriticalType($this->critical);
        $bmw->addCritical($critical);

        $bmw->addExtrafield($this->extraField);
        $bmw->addCustomfield($this->customField);
        $this->documentManager->persist($bmw);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $bmw->getName() . '(' . $bmw->getId() . ').');
    }//Default one handed

    public function addAdvancedMeleeWeapon()
    {
        $amw = new AdvancedMeleeWeaponRpItem();
        $amw->setName(TestNamingService::TEST_ADVANCED_MELEE_WEAPON_ITEM_NAME);
        $amw->setAvailable(true);
        $amw->setDepository($this->depository);
        $amw->setLevel(0);
        $amw->setSourceBook($this->book);
        $amw->setSourcePage('testP');
        $amw->setCost(1200, "credit");
        $amw->setNumberOfHandNeeded(2);
        $amw->setDamages($this->stringMapper->getDamagesFromString('1d8 Te&st', $this->depository));

        $critical = new Critical();
        $critical->setCriticalType($this->critical);
        $amw->addCritical($critical);

        $amw->addExtrafield($this->extraField);
        $amw->addCustomfield($this->customField);
        $this->documentManager->persist($amw);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $amw->getName() . '(' . $amw->getId() . ').');
    }

    public function addSmallArm()
    {
        $saw = new SmallArmRpItem();
        $saw->setName(TestNamingService::TEST_SMALL_ARMS_ITEM_NAME);
        $saw->setAvailable(true);
        $saw->setDepository($this->depository);
        $saw->setSourceBook($this->book);
        $saw->setSourcePage('testP');
        $saw->setNumberOfHandNeeded(1);
        $saw->setLevel(0);
        $saw->setCost(1200, "credit");
        $saw->setDamages($this->stringMapper->getDamagesFromString('1d4 Te&st', $this->depository));
        $saw->setRange($this->stringMapper->getRangeFromString('20 ft.'));

        $capAndUsage = new CapacityAndUsage();
        $capAndUsage->setCapacity(20);
        $capAndUsage->setUsage(1);
        $capAndUsage->setCapacityType($this->ammunitionType);
        $saw->addCapacityAndUsage($capAndUsage);

        $critical = new Critical();
        $critical->setCriticalType($this->critical);
        $saw->addCritical($critical);

        $saw->addExtrafield($this->extraField);
        $saw->addCustomfield($this->customField);
        $this->documentManager->persist($saw);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $saw->getName() . '(' . $saw->getId() . ').');
    }

    public function addLongarm()
    {
        $la = new LongarmRpItem();
        $la->setName(TestNamingService::TEST_LONGARM_ITEM_NAME);
        $la->setAvailable(true);
        $la->setDepository($this->depository);
        $la->setSourceBook($this->book);
        $la->setSourcePage('testP');
        $la->setNumberOfHandNeeded(1);
        $la->setLevel(0);
        $la->setCost(1200, "credit");
        $la->setDamages($this->stringMapper->getDamagesFromString('1d6 Te& st', $this->depository));
        $la->setRange($this->stringMapper->getRangeFromString('80ft.'));

        $capAndUsage = new CapacityAndUsage();
        $capAndUsage->setCapacity(10);
        $capAndUsage->setUsage(2);
        $capAndUsage->setCapacityType($this->ammunitionType);
        $la->addCapacityAndUsage($capAndUsage);

        $critical = new Critical();
        $critical->setCriticalType($this->critical);
        $la->addCritical($critical);

        $la->addExtrafield($this->extraField);
        $la->addCustomfield($this->customField);
        $this->documentManager->persist($la);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $la->getName() . '(' . $la->getId() . ').');
    }

    public function addHeavyWeapon()
    {
        $hw = new HeavyWeaponRpItem();
        $hw->setName(TestNamingService::TEST_HEAVY_WEAPON_ITEM_NAME);
        $hw->setAvailable(true);
        $hw->setDepository($this->depository);
        $hw->setSourceBook($this->book);
        $hw->setSourcePage('testP');
        $hw->setNumberOfHandNeeded(1);
        $hw->setLevel(0);
        $hw->setCost(1500, "credit");
        $hw->setDamages($this->stringMapper->getDamagesFromString('2d4 Te&st', $this->depository));
        $hw->setRange($this->stringMapper->getRangeFromString('120 ft.'));

        $capAndUsage = new CapacityAndUsage();
        $capAndUsage->setCapacity(20);
        $capAndUsage->setUsage(2);
        $capAndUsage->setCapacityType($this->ammunitionType);
        $hw->addCapacityAndUsage($capAndUsage);

        $critical = new Critical();
        $critical->setCriticalType($this->critical);
        $hw->addCritical($critical);

        $hw->addExtrafield($this->extraField);
        $hw->addCustomfield($this->customField);
        $this->documentManager->persist($hw);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $hw->getName() . '(' . $hw->getId() . ').');
    }

    public function addSniperWeapon()
    {
        $saw = new SniperWeaponRpItem();
        $saw->setName(TestNamingService::TEST_SNIPER_WEAPON_ITEM_NAME);
        $saw->setAvailable(true);
        $saw->setDepository($this->depository);
        $saw->setSourceBook($this->book);
        $saw->setSourcePage('testP');
        $saw->setNumberOfHandNeeded(1);
        $saw->setLevel(0);
        $saw->setCost(1200, "credit");
        $saw->setDamages($this->stringMapper->getDamagesFromString('1d8 Te&st', $this->depository));
        $saw->setRange($this->stringMapper->getRangeFromString('140 ft.'));

        $capAndUsage = new CapacityAndUsage();
        $capAndUsage->setCapacity(10);
        $capAndUsage->setUsage(1);
        $capAndUsage->setCapacityType($this->ammunitionType);
        $saw->addCapacityAndUsage($capAndUsage);

        $critical = new Critical();
        $critical->setCriticalType($this->critical);
        $saw->addCritical($critical);

        $saw->addExtrafield($this->extraField);
        $saw->addCustomfield($this->customField);
        $this->documentManager->persist($saw);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $saw->getName() . '(' . $saw->getId() . ').');
    }

    public function addSpecialWeapon()
    {
        $saw = new SpecialWeaponRpItem();
        $saw->setName(TestNamingService::TEST_SPECIAL_WEAPON_ITEM_NAME);
        $saw->setAvailable(true);
        $saw->setDepository($this->depository);
        $saw->setSourceBook($this->book);
        $saw->setSourcePage('testP');
        $saw->setNumberOfHandNeeded(1);
        $saw->setLevel(0);
        $saw->setCost(1200, "credit");
        $saw->setDamages($this->stringMapper->getDamagesFromString('1d6 Te&st', $this->depository));
        $saw->setRange($this->stringMapper->getRangeFromString('30 ft.'));

        $capAndUsage = new CapacityAndUsage();
        $capAndUsage->setCapacity(50);
        $capAndUsage->setUsage(4);
        $capAndUsage->setCapacityType($this->ammunitionType);
        $saw->addCapacityAndUsage($capAndUsage);

        $critical = new Critical();
        $critical->setCriticalType($this->critical);
        $saw->addCritical($critical);

        $saw->addExtrafield($this->extraField);
        $saw->addCustomfield($this->customField);
        $this->documentManager->persist($saw);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $saw->getName() . '(' . $saw->getId() . ').');
    }

    public function addSolarianCrystalWeapon()
    {
        $scw = new SolarianWeaponCrystalRpItem();
        $scw->setName(TestNamingService::TEST_SOLARIAN_CRYSTAL_WEAPON_NAME);
        $scw->setAvailable(true);
        $scw->setDepository($this->depository);
        $scw->setSourceBook($this->book);
        $scw->setSourcePage('testP');
        $scw->setLevel(0);
        $scw->setBulk('-');
        $scw->setCost(100, "credit");
        $scw->setModificatorDamages($this->stringMapper->getModificatorDamagesFromString('+1d4 Te&st', $this->depository));

        $critical = new Critical();
        $critical->setCriticalType($this->critical);
        $scw->addCritical($critical);

        $scw->addExtrafield($this->extraField);
        $scw->addCustomfield($this->customField);
        $this->documentManager->persist($scw);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $scw->getName() . '(' . $scw->getId() . ').');
    }

    private function initializeTestVars()
    {
        $this->depository = $this->documentManager->getRepository(Depository::class)->findOneBy([
            'name' => TestNamingService::TEST_DEPOSITORY_NAME,
        ]);

        if (!$this->depository instanceof Depository) {
            throw new \Exception('No Test Depository found, please start by the <app:test:add:depository> command.');
        }

        $this->book = $this->documentManager->getRepository(Book::class)->findOneBy([
            'title' => TestNamingService::TEST_BOOK_TITLE,
        ]);

        if (!$this->book instanceof Book) {
            throw new \Exception('No Test Book found, please start by the <app:test:add:book> command.');
        }

        $this->specialDamage = $this->documentManager->getRepository(SpecialType::class)->findOneBy([
            'name' => TestNamingService::TEST_SPECIAL_DAMAGE_NAME,
        ]);
        $this->specialExplosion = $this->documentManager->getRepository(SpecialType::class)->findOneBy([
            'name' => TestNamingService::TEST_SPECIAL_EXPLOSION_NAME,
        ]);

        if (!$this->specialDamage instanceof SpecialType || !$this->specialExplosion instanceof SpecialType) {
            throw new \Exception('Not enough Test Special found, please start by the <app:test:add:special> command.');
        }

        $this->critical = $this->documentManager->getRepository(CriticalType::class)->findOneBy([
            'name' => TestNamingService::TEST_CRITICAL_NAME,
        ]);

        if (!$this->critical instanceof CriticalType) {
            throw new \Exception('No Test CriticalType found, please start by the <app:test:add:critical> command.');
        }

        $this->weaponCategory = $this->documentManager->getRepository(WeaponCategory::class)->findOneBy([
            'name' => TestNamingService::TEST_WEAPON_CATEGORY_NAME,
        ]);

        if (!$this->weaponCategory instanceof WeaponCategory) {
            throw new \Exception('No Test WeaponCategory found, please start by the <app:test:add:weapon-category> command.');
        }

        $this->damageType = $this->documentManager->getRepository(DamageType::class)->findOneBy([
            'name' => TestNamingService::TEST_DAMAGE_TYPE_NAME,
        ]);

        if (!$this->damageType instanceof DamageType) {
            throw new \Exception('No Test damageType found, please start by the <app:test:add:damage-type> command.');
        }

        $this->familyDescription = $this->documentManager->getRepository(FamilyDescription::class)->findOneBy([
            'name' => TestNamingService::TEST_FAMILY_DESCRIPTION_NAME,
        ]);

        if (!$this->familyDescription instanceof FamilyDescription) {
            throw new \Exception('No Test familyDescription found, please start by the <app:test:add:family-description> command.');
        }

        $this->ammunitionType = $this->documentManager->getRepository(CapacityType::class)->findOneBy([
            'name' => TestNamingService::TEST_AMMUNITION_TYPE_NAME,
        ]);

        if (!$this->ammunitionType instanceof CapacityType) {
            throw new \Exception('No Test ammunitionType found, please start by the <app:test:add:ammunition-type> command.');
        }
    }
}

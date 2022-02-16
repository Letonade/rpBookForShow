<?php

declare(strict_types=1);

namespace App\Command\Test\Depository;

use App\Document\Depository\Depository;
use App\Document\Depository\RpItem\AugmentRpItem;
use App\Document\Depository\Properties\WeaponCategory\WeaponCategory;
use App\Document\Book\Book;
use App\Document\Common\Customfield\Customfield;
use App\Document\Common\Extrafield\Extrafield;
use App\Document\Order\Order;
use App\Service\Plugin\Como\ComoService;
use App\Service\TestNaming\TestNamingService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestAddWeaponCategory extends Command
{
    protected static $defaultName = 'app:test:add:weapon-category';

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

    public function __construct(
        DocumentManager $documentManager,
        ManagerRegistry $managerRegistry,
        LoggerInterface $testsLogger,
        string $name = null
    ) {
        parent::__construct($name);
        $this->documentManager = $documentManager;
        $this->managerRegistry = $managerRegistry;
        $this->testsLogger = $testsLogger;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->testsLogger->info('Starting ' . self::$defaultName . ' command.');

        $depository = $this->documentManager->getRepository(Depository::class)->findOneBy([
            'name' => TestNamingService::TEST_DEPOSITORY_NAME,
        ]);

        if (!$depository instanceof Depository) {
            throw new \Exception('No Test Depository found, please start by the <app:test:add:depository> command.');
        }

        $weaponCategory = new WeaponCategory();
        $weaponCategory->setName(TestNamingService::TEST_WEAPON_CATEGORY_NAME);
        $weaponCategory->setDescription('Test Desc');
        $weaponCategory->setDepository($depository);
        $this->documentManager->persist($weaponCategory);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $weaponCategory->getName() . '(' . $weaponCategory->getId() . ').');

        return 1;
    }
}

<?php

declare(strict_types=1);

namespace App\Command\Test\Depository;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\CapacityType\CapacityType;
use App\Document\Depository\Properties\FamilyDescription\FamilyDescription;
use App\Document\Order\Order;
use App\Service\Plugin\Como\ComoService;
use App\Service\TestNaming\TestNamingService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestAddFamilyDescription extends Command
{
    protected static $defaultName = 'app:test:add:family-descrition';

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

        $familyDescription = new FamilyDescription();
        $familyDescription->setName(TestNamingService::TEST_FAMILY_DESCRIPTION_NAME);
        $familyDescription->setDepository($depository);
        $familyDescription->setDescription("Normally every weapon families have their own descriptions but as a test we will "
                                        ."use this only description for all of them");
        $this->documentManager->persist($familyDescription);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $familyDescription->getName() . '(' . $familyDescription->getId() . ').');

        return 1;
    }
}

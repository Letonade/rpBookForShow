<?php

declare(strict_types=1);

namespace App\Command\Test\Depository;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\CapacityType\CapacityType;
use App\Document\Order\Order;
use App\Service\Plugin\Como\ComoService;
use App\Service\TestNaming\TestNamingService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestAddCapacityType extends Command
{
    protected static $defaultName = 'app:test:add:capacity-type';

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

        $ammuntionType = new CapacityType();
        $ammuntionType->setName(TestNamingService::TEST_AMMUNITION_TYPE_NAME);
        $ammuntionType->setPlural(TestNamingService::TEST_AMMUNITION_TYPE_NAME);
        $ammuntionType->setDepository($depository);
        $this->documentManager->persist($ammuntionType);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $ammuntionType->getName() . '(' . $ammuntionType->getId() . ').');

        return 1;
    }
}

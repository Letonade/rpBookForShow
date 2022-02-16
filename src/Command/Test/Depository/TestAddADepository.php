<?php

declare(strict_types=1);

namespace App\Command\Test\Depository;

use App\Document\Depository\Depository;
use App\Document\Order\Order;
use App\Service\Plugin\Como\ComoService;
use App\Service\TestNaming\TestNamingService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestAddADepository extends Command
{
    protected static $defaultName = 'app:test:add:depository';

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
        $this->testsLogger->info('Starting '.self::$defaultName.' command.');
        $depository = new Depository();
        //Don't change the name
        $depository->setName(TestNamingService::TEST_DEPOSITORY_NAME);
        $depository->setAvailable(true);
        $depository->setDescription("The Depository for all the starfinders campaign but firstly for my campaing :}");
        $this->documentManager->persist($depository);
        $this->documentManager->flush();
        $this->testsLogger->info('Added '.$depository->getName().'('.$depository->getId().').');


        return 1;
    }
}

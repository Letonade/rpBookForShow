<?php

declare(strict_types=1);

namespace App\Command\Test;

use App\Document\Order\Order;
use App\Service\Plugin\Como\ComoService;
use App\Service\RpItemPropertiesAsString\RpItemPropertiesAsStringMapper;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestAllInOne extends Command
{
    protected static $defaultName = 'app:test:add:all';

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

        $command = $this->getApplication()->find('app:test:add:delete-old-test-by-naming');
        $command->run(new ArrayInput([]), $output);

        // $greetInput = new ArrayInput($arguments);
        $command = $this->getApplication()->find('app:test:add:depository');
        $command->run(new ArrayInput([]), $output);

        $command = $this->getApplication()->find('app:test:add:book');
        $command->run(new ArrayInput([]), $output);

        $command = $this->getApplication()->find('app:test:add:speed-movement-type');
        $command->run(new ArrayInput([]), $output);

        $command = $this->getApplication()->find('app:test:add:special');
        $command->run(new ArrayInput([]), $output);

        $command = $this->getApplication()->find('app:test:add:critical');
        $command->run(new ArrayInput([]), $output);

        $command = $this->getApplication()->find('app:test:add:weapon-category');
        $command->run(new ArrayInput([]), $output);

        $command = $this->getApplication()->find('app:test:add:damage-type');
        $command->run(new ArrayInput([]), $output);

        $command = $this->getApplication()->find('app:test:add:capacity-type');
        $command->run(new ArrayInput([]), $output);

        $command = $this->getApplication()->find('app:test:add:family-descrition');
        $command->run(new ArrayInput([]), $output);

        $command = $this->getApplication()->find('app:test:add:items');
        $command->run(new ArrayInput([]), $output);


        $this->testsLogger->info('End of the command ' . self::$defaultName);

        return 1;
    }
}

<?php

declare(strict_types=1);

namespace App\Command\Test;

use App\Document\Order\Order;
use App\Service\Plugin\Como\ComoService;
use App\Service\TestNaming\TestNamingService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestDeleteAllTestDataByName extends Command
{
    protected static $defaultName = 'app:test:add:delete-old-test-by-naming';

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
        $this->testsLogger->info('Starting deleting old test by naming.');
        foreach (TestNamingService::FINDER_BY_NAME as $key => $value) {
            $toDelete = $this->documentManager->getRepository($value['class'])->findBy([
                $value['findBy'] => $key,
            ]);
            if (count($toDelete) > 0) {
                foreach ($toDelete as $deleting) {
                    $this->documentManager->remove($deleting);
                    $this->documentManager->flush();
                    $this->testsLogger->info('Deleting '.$key.'('.$deleting->getId().').');
                }
            }
        }
        $this->testsLogger->info('End of the command ' . self::$defaultName);

        return 1;
    }
}

<?php

declare(strict_types=1);

namespace App\Command\Test\Book;

use App\Document\Depository\Depository;
use App\Document\Book\Book;
use App\Document\Order\Order;
use App\Service\Plugin\Como\ComoService;
use App\Service\TestNaming\TestNamingService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestAddBook extends Command
{
    protected static $defaultName = 'app:test:add:book';

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
        $book = new Book();
        //Don't change the name
        $book->setTitle(TestNamingService::TEST_BOOK_TITLE);
        $book->setAcronym('YOLO');
        $book->setAvailable(true);
        $book->setDescription('All in this are not meta bitchyzzz');

        $this->documentManager->persist($book);
        $this->documentManager->flush();
        $this->testsLogger->info('Added '.$book->getTitle().'('.$book->getId().').');

        return 1;
    }
}

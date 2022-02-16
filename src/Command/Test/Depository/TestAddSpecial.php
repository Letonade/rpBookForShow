<?php

declare(strict_types=1);

namespace App\Command\Test\Depository;

use App\Document\Depository\Depository;
use App\Document\Depository\RpItem\AugmentRpItem;
use App\Document\Depository\Properties\Special\SpecialType;
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

class TestAddSpecial extends Command
{
    protected static $defaultName = 'app:test:add:special';

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

        $book = $this->documentManager->getRepository(Book::class)->findOneBy([
            'title' => TestNamingService::TEST_BOOK_TITLE,
        ]);

        if (!$book instanceof Book) {
            throw new \Exception('No Test Book found, please start by the <app:test:add:book> command.');
        }

        //Damage
        $special1 = new SpecialType();
        $special1->setName(TestNamingService::TEST_SPECIAL_DAMAGE_NAME);
        $special1->setDepository($depository);
        $special1->setSourceBook($book);
        $special1->setLabel('');
        $special1->setStartDelimiter('');
        $special1->setEndDelimiter('');
        $special1->setDescription('Lorem Ipsum dolor ..');
        $special1->setAvailable(true);
        $this->documentManager->persist($special1);

        //Explosion
        $special2 = new SpecialType();
        $special2->setName(TestNamingService::TEST_SPECIAL_EXPLOSION_NAME);
        $special2->setDepository($depository);
        $special2->setSourceBook($book);
        $special2->setLabel('Explosion');
        $special2->setStartDelimiter('(');
        $special2->setEndDelimiter(')');
        $special2->setDescription('Lorem Ipsum dolor .. EXXXPLLoOooSIONn !!!');
        $special2->setAvailable(true);

        $special2->addSubSpecial($special1);

        $EFaugmentation = new Extrafield();
        $EFaugmentation->setPublic(true);
        $EFaugmentation->setValue('test');
        $EFaugmentation->setHandle('EF-TEST');

        $special2->addExtrafield($EFaugmentation);
        $this->documentManager->persist($special2);

        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $special1->getName() . '(' . $special1->getId() . ').');
        $this->testsLogger->info('Added ' . $special2->getName() . '(' . $special2->getId() . ').');

        return 1;
    }
}

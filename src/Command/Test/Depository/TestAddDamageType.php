<?php

declare(strict_types=1);

namespace App\Command\Test\Depository;

use App\Document\Depository\Depository;
use App\Document\Depository\RpItem\AugmentRpItem;
use App\Document\Depository\Properties\DamageType\DamageType;
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

class TestAddDamageType extends Command
{
    protected static $defaultName = 'app:test:add:damage-type';

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

        $damageType = new DamageType();
        $damageType->setName(TestNamingService::TEST_DAMAGE_TYPE_NAME);
        $damageType->setDepository($depository);
        $damageType->setAcronym('Te');
        $damageType->setDescription('Test Desc');
        $this->documentManager->persist($damageType);
        $this->documentManager->flush();
        $this->testsLogger->info('Added ' . $damageType->getName() . '(' . $damageType->getId() . ').');

        $damageType2 = new DamageType();
        $damageType2->setName(TestNamingService::TEST_DAMAGE_TYPE_NAME_2);
        $damageType2->setDepository($depository);
        $damageType2->setAcronym('St');
        $damageType2->setDescription('Test Desc');
        $this->documentManager->persist($damageType2);
        $this->documentManager->flush();

        $this->testsLogger->info('Added ' . $damageType2->getName() . '(' . $damageType2->getId() . ').');

        return 1;
    }
}

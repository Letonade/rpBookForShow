<?php

declare(strict_types=1);

namespace App\Controller\FrontTwig\Depository;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Document\Depository\Depository;
use App\Document\Depository\Properties\RpItemTypeDescription\RpItemTypeDescription;
use App\Document\Depository\RpItem\Fusion\WeaponFusionRpItem;
use App\Document\Depository\RpItem\Structure\DiscriminatoryRpItem;
use App\Document\Shop;
use App\Entity\Role;
use App\Form\FrontTwig\Depository\FilterData\FrontTwigDepositoryRpItemFilterData;
use App\Form\FrontTwig\Depository\FrontTwigDepositoryRpItemFilterForm;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class FrontTwigDepositoryFusionListAction extends AbstractController
{

    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function __construct(
        DocumentManager $documentManager,
        ManagerRegistry $managerRegistry,
        ValidatorInterface $validator
    ) {
        $this->documentManager = $documentManager;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @throws Exception
     */
    public function __invoke(Request $request)
    {
        $type = $request->get('type');

        if (!in_array($type, DiscriminatoryRpItem::DISCRIMINATOR_MAP)) {
            throw new Exception("Not a valid RpItem or not in the DISCRIMINATOR_MAP.");
        }

        $depositoryList = $this->documentManager->getRepository(Depository::class)->findAll();

        $typeTable = [
            'WeaponFusion' => false,
        ];
        $typeTable[$type] = true;

        $itemTypeDesc = $this->documentManager->getRepository(RpItemTypeDescription::class)->findOneBy(['discriminatorName' => $type]);
        if (!$itemTypeDesc instanceof RpItemTypeDescription) {
            throw new Exception('Item type description not found.');
        }
        $hint = $itemTypeDesc->getDescription();
        $tableTitle = $itemTypeDesc->getName();

        $generalSorter = ['level' => 'ASC', 'cost.value' => 'ASC', 'numberOfHandNeeded' => 'ASC'];
        // find the corresponding items.
        $filterData = new FrontTwigDepositoryRpItemFilterData();
        $filterForm = $this->createForm(FrontTwigDepositoryRpItemFilterForm::class, $filterData);
        $filterForm->handleRequest($request);
        $collection = $this->documentManager->getRepository(DiscriminatoryRpItem::class)->findWithFilterForm($type,$generalSorter,$filterData);
        return $this->render('Depository/fusionList.html.twig', [
            'typeTable'  => $typeTable,
            'collection' => $collection,
            'tableTitle' => $tableTitle,
            'hint'       => $hint,
            'filterForm' => $filterForm->createView(),
        ]);
    }
}

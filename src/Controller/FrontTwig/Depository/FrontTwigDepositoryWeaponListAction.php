<?php

declare(strict_types=1);

namespace App\Controller\FrontTwig\Depository;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Document\Depository\Depository;
use App\Document\Depository\Properties\RpItemTypeDescription\RpItemTypeDescription;
use App\Document\Depository\RpItem\Weapon\AdvancedMeleeWeaponRpItem;
use App\Document\Depository\RpItem\BattleWare\AmmunitionRpItem;
use App\Document\Depository\RpItem\Weapon\BasicMeleeWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\HeavyWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\LongarmRpItem;
use App\Document\Depository\RpItem\Weapon\SmallArmRpItem;
use App\Document\Depository\RpItem\Weapon\SniperWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\SolarianWeaponCrystalRpItem;
use App\Document\Depository\RpItem\Weapon\SpecialWeaponRpItem;
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

class FrontTwigDepositoryWeaponListAction extends AbstractController
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
            'BasicMeleeWeapon'      => false,
            'AdvancedMeleeWeapon'   => false,
            'SmallArm'              => false,
            'Longarm'               => false,
            'HeavyWeapon'           => false,
            'SniperWeapon'          => false,
            'SpecialWeapon'         => false,
            'SolarianWeaponCrystal' => false,
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

        return $this->render('Depository/weaponList.html.twig', [
            'typeTable'  => $typeTable,
            'collection' => $collection,
            'tableTitle' => $tableTitle,
            'hint'       => $hint,
            'filterForm' => $filterForm->createView(),
        ]);
    }
}




// switch ($type) {
//     case "BasicMeleeWeapon":
//         $sorter = array_merge([], $generalSorter);
//         $collection = $this->documentManager->getRepository(BasicMeleeWeaponRpItem::class)->findBy([],
//             $sorter);
//         break;
//     case "AdvancedMeleeWeapon":
//         $sorter = array_merge([], $generalSorter);
//         $collection = $this->documentManager->getRepository(AdvancedMeleeWeaponRpItem::class)->findBy([],
//             $sorter);
//         break;
//     case "SmallArm":
//         $sorter = array_merge([], $generalSorter);
//         $collection = $this->documentManager->getRepository(SmallArmRpItem::class)->findBy([], $sorter);
//         break;
//     case "Longarm":
//         $sorter = array_merge([], $generalSorter);
//         $collection = $this->documentManager->getRepository(LongarmRpItem::class)->findBy([], $sorter);
//         break;
//     case "HeavyWeapon":
//         $sorter = array_merge([], $generalSorter);
//         $collection = $this->documentManager->getRepository(HeavyWeaponRpItem::class)->findBy([],
//             $sorter);
//         break;
//     case "SniperWeapon":
//         $sorter = array_merge([], $generalSorter);
//         $collection = $this->documentManager->getRepository(SniperWeaponRpItem::class)->findBy([],
//             $sorter);
//         break;
//     case "SpecialWeapon":
//         $sorter = array_merge([], $generalSorter);
//         $collection = $this->documentManager->getRepository(SpecialWeaponRpItem::class)->findBy([],
//             $sorter);
//         break;
//     case "SolarianWeaponCrystal":
//         $sorter = array_merge([], $generalSorter);
//         $collection = $this->documentManager->getRepository(SolarianWeaponCrystalRpItem::class)->findBy([],
//             $sorter);
//         break;
// }

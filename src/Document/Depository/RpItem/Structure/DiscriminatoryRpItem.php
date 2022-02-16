<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure;

// In this file we add the different type of items,
// 1> create a class of item
// 2> add it here in the discriminator map
// 3> Create the associated family description and link them to a unique family slug (for import/export it's a must)
// 4> If any new type is needed like moveSpeedType or TechType please be sure to check if it doesn't already exist.
// 5> Create a CSV corresponding file, and it's import; why? because all player can't read db but most of them can read Excel and update.
// !!> For complexe CSV parsing use the RpItemPropertiesAsString service, it's a tool to parse data as it is written in the book. you can implement it.
// Specification -> "type" is the database field we will use, don't override it.
// Only abstract class should extend this class.

use App\Document\Depository\RpItem\ArmorUpgrade\ArmorUpgradeRpItem;
use App\Document\Depository\RpItem\Weapon\AdvancedMeleeWeaponRpItem;
use App\Document\Depository\RpItem\BattleWare\AmmunitionRpItem;
use App\Document\Depository\RpItem\AugmentRpItem;
use App\Document\Depository\RpItem\Weapon\BasicMeleeWeaponRpItem;
use App\Document\Depository\RpItem\BattleWare\GrenadeRpItem;
use App\Document\Depository\RpItem\Weapon\HeavyWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\LongarmRpItem;
use App\Document\Depository\RpItem\Weapon\SmallArmRpItem;
use App\Document\Depository\RpItem\Weapon\SniperWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\SolarianWeaponCrystalRpItem;
use App\Document\Depository\RpItem\Weapon\SpecialWeaponRpItem;
use App\Document\Depository\RpItem\Fusion\WeaponFusionRpItem;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\DiscriminatorField;
use Doctrine\ODM\MongoDB\Mapping\Annotations\DiscriminatorMap;
use Doctrine\ODM\MongoDB\Mapping\Annotations\InheritanceType;
use App\Document\Depository\RpItem\Armor\LightArmorRpItem;
use App\Document\Depository\RpItem\Armor\HeavyArmorRpItem;
use App\Document\Depository\RpItem\Armor\PoweredArmorRpItem;

/**
 * @MongoDB\Document(collection="Items", repositoryClass="App\Repository\Depository\RpItemRepository")
 * @InheritanceType("SINGLE_COLLECTION")
 * @DiscriminatorField("type")
 * @DiscriminatorMap({
 *     "Augmentation"=AugmentRpItem::class,
 *     "ArmorUpgrade"=ArmorUpgradeRpItem::class,
 *
 *     "Grenade"=GrenadeRpItem::class,
 *     "Ammunition"=AmmunitionRpItem::class,
 *
 *     "WeaponFusion"=WeaponFusionRpItem::class,
 *
 *     "BasicMeleeWeapon"=BasicMeleeWeaponRpItem::class,
 *     "AdvancedMeleeWeapon"=AdvancedMeleeWeaponRpItem::class,
 *     "SmallArm"=SmallArmRpItem::class,
 *     "Longarm"=LongarmRpItem::class,
 *     "HeavyWeapon"=HeavyWeaponRpItem::class,
 *     "SniperWeapon"=SniperWeaponRpItem::class,
 *     "SpecialWeapon"=SpecialWeaponRpItem::class,
 *     "SolarianWeaponCrystal"=SolarianWeaponCrystalRpItem::class,
 *
 *     "LightArmor"=LightArmorRpItem::class,
 *     "HeavyArmor"=HeavyArmorRpItem::class,
 *     "PoweredArmor"=PoweredArmorRpItem::class,
 *     })
 */
class DiscriminatoryRpItem
{
    public const DOCUMENT_NAME = 'DiscriminatoryRpItem';
    public const DISCRIMINATOR_MAP = [
        "Augmentation",
        "ArmorUpgrade",
        "Grenade",
        "Ammunition",
        "WeaponFusion",
        "BasicMeleeWeapon",
        "AdvancedMeleeWeapon",
        "SmallArm",
        "Longarm",
        "HeavyWeapon",
        "SniperWeapon",
        "SpecialWeapon",
        "SolarianWeaponCrystal",
        "LightArmor",
        "HeavyArmor",
        "PoweredArmor",
    ];

    /**
     * @var string
     * @MongoDB\Id(type="id")
     */
    protected $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

}

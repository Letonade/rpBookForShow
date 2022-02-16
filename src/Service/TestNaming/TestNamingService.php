<?php
/**
 * @author Letonade
 * @date   06/11/2021 19:56
 */

namespace App\Service\TestNaming;


use App\Document\Depository\Depository;
use App\Document\Depository\Properties\SpeedMovement\SpeedMode;
use App\Document\Depository\Properties\SpeedMovement\SpeedMovementType;
use App\Document\Depository\RpItem\AugmentRpItem;
use App\Document\Depository\RpItem\BattleWare\AmmunitionRpItem;
use App\Document\Depository\RpItem\BattleWare\GrenadeRpItem;
use App\Document\Depository\Properties\CapacityType\CapacityType;
use App\Document\Depository\Properties\Critical\CriticalType;
use App\Document\Depository\Properties\DamageType\DamageType;
use App\Document\Depository\Properties\FamilyDescription\FamilyDescription;
use App\Document\Depository\Properties\Special\SpecialType;
use App\Document\Depository\Properties\WeaponCategory\WeaponCategory;
use App\Document\Depository\RpItem\Weapon\AdvancedMeleeWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\BasicMeleeWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\HeavyWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\LongarmRpItem;
use App\Document\Depository\RpItem\Weapon\SmallArmRpItem;
use App\Document\Depository\RpItem\Weapon\SniperWeaponRpItem;
use App\Document\Depository\RpItem\Weapon\SolarianWeaponCrystalRpItem;
use App\Document\Depository\RpItem\Weapon\SpecialWeaponRpItem;
use App\Document\Book\Book;

abstract class TestNamingService
{
    private const ONE_TIME_HASH = ' #99bfa6';

    public const TEST_DEPOSITORY_NAME = "Test Depository" . self::ONE_TIME_HASH;
    public const TEST_BOOK_TITLE = "Test Book" . self::ONE_TIME_HASH;

    public const TEST_SPECIAL_DAMAGE_NAME = "Test Special Damage" . self::ONE_TIME_HASH;
    public const TEST_SPECIAL_EXPLOSION_NAME = "Test Special Explosion" . self::ONE_TIME_HASH;
    public const TEST_CRITICAL_NAME = "Test Critical Not Bleed" . self::ONE_TIME_HASH;
    public const TEST_WEAPON_CATEGORY_NAME = "Test Weapon Category" . self::ONE_TIME_HASH;
    public const TEST_DAMAGE_TYPE_NAME = "Test Damage Type" . self::ONE_TIME_HASH;
    public const TEST_DAMAGE_TYPE_NAME_2 = "Test Damage Type 2" . self::ONE_TIME_HASH;
    public const TEST_AMMUNITION_TYPE_NAME = "Test Ammunition Type" . self::ONE_TIME_HASH;
    public const TEST_SPEED_MOVEMENT_TYPE_NAME = "Test Speed Mode Type" . self::ONE_TIME_HASH;

    public const TEST_FAMILY_DESCRIPTION_NAME = "Test Family Description" . self::ONE_TIME_HASH;

    public const TEST_AMMUNITION_NAME = "Test Ammunition Item" . self::ONE_TIME_HASH;
    public const TEST_AUGMENT_ITEM_NAME = "Test Augment Item" . self::ONE_TIME_HASH;
    public const TEST_GRENADE_ITEM_NAME = "Test Grenade Item" . self::ONE_TIME_HASH;
    public const TEST_BASIC_MELEE_WEAPON_ITEM_NAME = "Test Basic melee weapon Item" . self::ONE_TIME_HASH;
    public const TEST_ADVANCED_MELEE_WEAPON_ITEM_NAME = "Test Advanced melee weapon Item" . self::ONE_TIME_HASH;
    public const TEST_SMALL_ARMS_ITEM_NAME = "Test Small arm Item" . self::ONE_TIME_HASH;
    public const TEST_LONGARM_ITEM_NAME = "Test Longarm Item" . self::ONE_TIME_HASH;
    public const TEST_HEAVY_WEAPON_ITEM_NAME = "Test Heavy weapon Item" . self::ONE_TIME_HASH;
    public const TEST_SNIPER_WEAPON_ITEM_NAME = "Test Sniper weapon Item" . self::ONE_TIME_HASH;
    public const TEST_SPECIAL_WEAPON_ITEM_NAME = "Test Special weapon Item" . self::ONE_TIME_HASH;
    public const TEST_SOLARIAN_CRYSTAL_WEAPON_NAME = "Test Solarian crystal weapon Item" . self::ONE_TIME_HASH;

    public const FINDER_BY_NAME = [
        self::TEST_DEPOSITORY_NAME => ['class' => Depository::class, 'findBy' => 'name'],
        self::TEST_BOOK_TITLE  => ['class' => Book::class, 'findBy' => 'title'],

        self::TEST_SPECIAL_DAMAGE_NAME      => ['class' => SpecialType::class, 'findBy' => 'name'],
        self::TEST_SPECIAL_EXPLOSION_NAME   => ['class' => SpecialType::class, 'findBy' => 'name'],
        self::TEST_CRITICAL_NAME            => ['class' => CriticalType::class, 'findBy' => 'name'],
        self::TEST_WEAPON_CATEGORY_NAME     => ['class' => WeaponCategory::class, 'findBy' => 'name'],
        self::TEST_DAMAGE_TYPE_NAME         => ['class' => DamageType::class, 'findBy' => 'name'],
        self::TEST_DAMAGE_TYPE_NAME_2       => ['class' => DamageType::class, 'findBy' => 'name'],
        self::TEST_AMMUNITION_TYPE_NAME     => ['class' => CapacityType::class, 'findBy' => 'name'],
        self::TEST_SPEED_MOVEMENT_TYPE_NAME => ['class' => SpeedMovementType::class, 'findBy' => 'name'],

        self::TEST_FAMILY_DESCRIPTION_NAME => ['class' => FamilyDescription::class, 'findBy' => 'name'],

        self::TEST_AMMUNITION_NAME                 => ['class' => AmmunitionRpItem::class, 'findBy' => 'name'],
        self::TEST_AUGMENT_ITEM_NAME               => ['class' => AugmentRpItem::class, 'findBy' => 'name'],
        self::TEST_GRENADE_ITEM_NAME               => ['class' => GrenadeRpItem::class, 'findBy' => 'name'],
        self::TEST_BASIC_MELEE_WEAPON_ITEM_NAME    => ['class' => BasicMeleeWeaponRpItem::class, 'findBy' => 'name'],
        self::TEST_ADVANCED_MELEE_WEAPON_ITEM_NAME => ['class' => AdvancedMeleeWeaponRpItem::class, 'findBy' => 'name'],
        self::TEST_SMALL_ARMS_ITEM_NAME            => ['class' => SmallArmRpItem::class, 'findBy' => 'name'],
        self::TEST_LONGARM_ITEM_NAME               => ['class' => LongarmRpItem::class, 'findBy' => 'name'],
        self::TEST_HEAVY_WEAPON_ITEM_NAME          => ['class' => HeavyWeaponRpItem::class, 'findBy' => 'name'],
        self::TEST_SNIPER_WEAPON_ITEM_NAME         => ['class' => SniperWeaponRpItem::class, 'findBy' => 'name'],
        self::TEST_SPECIAL_WEAPON_ITEM_NAME        => ['class' => SpecialWeaponRpItem::class, 'findBy' => 'name'],
        self::TEST_SOLARIAN_CRYSTAL_WEAPON_NAME    => [
            'class'  => SolarianWeaponCrystalRpItem::class,
            'findBy' => 'name',
        ],
    ];
}

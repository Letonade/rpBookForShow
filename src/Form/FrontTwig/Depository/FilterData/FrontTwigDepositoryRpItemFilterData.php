<?php

declare(strict_types=1);

namespace App\Form\FrontTwig\Depository\FilterData;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\Critical\CriticalType;
use App\Document\Depository\Properties\DamageType\DamageType;
use App\Document\Depository\Properties\Special\Special;
use App\Document\Depository\Properties\Special\SpecialType;
use App\Document\Depository\Properties\TechType\TechType;
use App\Document\Depository\RpItem\Structure\RpItem;
use App\Document\Book\Book;
use App\Document\Common\Assess\Assess;
use MongoDB\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FrontTwigDepositoryRpItemFilterData
{
    /** @var Depository[]|Collection|null */
    public $depositorys;
    /** @var Book[|null */
    public $sourceBooks;
    /** @var int|null */
    public $minCost;
    /** @var int|null */
    public $maxCost;
    /** @var int|null */
    public $minLevel;
    /** @var int|null */
    public $maxLevel;
    /** @var string|null */
    public $name;
    /** @var TechType[]|Collection|null */
    public $techTypes;
    /** @var DamageType[]|null */
    public $damageTypes;
    /** @var bool */
    public $damageTypesOr = true;
    /** @var int|null */
    public $minRawDamage;
    /** @var int|null */
    public $maxRawDamage;
    /** @var SpecialType[]|null */
    public $specialTypes;
    /** @var bool */
    public $specialTypesOr = true;
    /** @var CriticalType[]|null */
    public $criticalTypes;
    /** @var bool */
    public $criticalTypesOr = true;
}

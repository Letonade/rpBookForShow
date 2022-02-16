<?php

declare(strict_types=1);

namespace App\Document\Depository\RpItem\Structure;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\FamilyDescription\FamilyDescription;
use App\Document\Depository\Properties\RpItemTypeDescription\RpItemTypeDescription;
use App\Document\Book\Book;
use App\Document\Common\Assess\Assess;
use App\Document\Common\Customfield\Customfield;
use App\Document\Common\Customfield\DocumentWithCustomfieldsInterface;
use App\Document\Common\Customfield\DocumentWithCustomfieldsTrait;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsInterface;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsTrait;
use App\Document\Common\Extrafield\Extrafield;
use App\Document\Depository\Properties\TechType\TechType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

abstract class RpItem extends DiscriminatoryRpItem implements DocumentWithExtrafieldsInterface, DocumentWithCustomfieldsInterface
{
    use DocumentWithExtrafieldsTrait;
    use DocumentWithCustomfieldsTrait;

    public const DOCUMENT_NAME = 'RpItem';

    /**
     * @var Depository
     * @MongoDB\ReferenceOne(targetDocument=Depository::class, storeAs="id")
     */
    protected $depository;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $name;

    /**
     * @var FamilyDescription
     * @MongoDB\ReferenceOne(targetDocument=FamilyDescription::class, storeAs="id", name="family_description")
     */
    protected $familyDescription;

    // /**
    //  * @var RpItemTypeDescription
    //  * @MongoDB\ReferenceOne(targetDocument=RpItemTypeDescription::class, storeAs="id", name="rp_item_type_description")
    //  */
    // protected $rpItemTypeDescription;

    /**
     * @var bool
     * @MongoDB\Field(type="bool")
     */
    protected $available = false;

    /**
     * @var Assess
     * @MongoDB\EmbedOne(targetDocument=Assess::class)
     */
    protected $cost;

    /**
     * @var Book
     * @MongoDB\ReferenceOne(targetDocument=Book::class, storeAs="id", name="source_book")
     */
    protected $sourceBook;

    /**
     * @var string
     * @MongoDB\Field(type="string", name="source_page")
     */
    protected $sourcePage = 'p-unknown';

    /**
     * @var TechType
     * @MongoDB\ReferenceOne(targetDocument=TechType::class, storeAs="id", name="tech_type")
     */
    protected $techType;

    /**
     * @var Collection|Customfield[]
     * @MongoDB\EmbedMany(targetDocument=Customfield::class)
     */
    protected $customfields;

    /**
     * @var Collection|Extrafield[]
     * @MongoDB\EmbedMany(targetDocument=Extrafield::class)
     */
    protected $extrafields;

    public function __construct()
    {
        $this->extrafields = new ArrayCollection();
        $this->customfields = new ArrayCollection();
    }

    public function getDepository(): Depository
    {
        return $this->depository;
    }

    public function setDepository(Depository $depository): void
    {
        $this->depository = $depository;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFamilyDescription(): ?FamilyDescription
    {
        return $this->familyDescription;
    }

    public function setFamilyDescription(?FamilyDescription $familyDescription): void
    {
        $this->familyDescription = $familyDescription;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): void
    {
        $this->available = $available;
    }

    public function getCost(): Assess
    {
        return $this->cost;
    }

    public function setCostFromAssess(Assess $cost)
    {
        $this->cost = $cost;
    }

    public function setCost(int $costValue, string $costCurrency)
    {
        $cost = new Assess();
        $cost->setValue($costValue);
        $cost->setCurrency($costCurrency);
        $this->cost = $cost;
    }

    public function getSourceBook(): Book
    {
        return $this->sourceBook;
    }

    public function setSourceBook(Book $sourceBook): void
    {
        $this->sourceBook = $sourceBook;
    }

    public function getSourcePage(): string
    {
        return $this->sourcePage;
    }

    public function setSourcePage(string $sourcePage): void
    {
        $this->sourcePage = $sourcePage;
    }

    public function getTechType(): TechType
    {
        return $this->techType;
    }

    public function setTechType(TechType $techType): void
    {
        $this->techType = $techType;
    }

    // public function getRpItemTypeDescription(): RpItemTypeDescription
    // {
    //     return $this->rpItemTypeDescription;
    // }
    //
    // public function setRpItemTypeDescription(RpItemTypeDescription $rpItemTypeDescription): void
    // {
    //     $this->rpItemTypeDescription = $rpItemTypeDescription;
    // }
}

<?php

declare(strict_types=1);

namespace App\Document\Depository\Properties\Special;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\ConditionType\ConditionType;
use App\Document\Book\Book;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsInterface;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsTrait;
use App\Document\Common\Extrafield\Extrafield;
use App\Service\RpItemPropertiesAsString\SpecialStringMapper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Document\Depository\Properties\Special\SpecialType;
use Exception;

/**
 * @MongoDB\EmbeddedDocument()
 */
class Special
{
    /**
     * @var string
     * @MongoDB\Id(type="id")
     */
    protected $id;

    //Ex: "1d8+2, fire" wil get "Explosion(1d8+2, fire)"
    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $value = '';

    /**
     * @var SpecialType
     * @MongoDB\ReferenceOne (targetDocument=SpecialType::class, storeAs="id", name="special_type")
     */
    protected $specialType;

    /**
     * @var Collection|SpecialType[]
     * @MongoDB\ReferenceMany (targetDocument=SpecialType::class, storeAs="id", name="other_sub_specials")
     */
    protected $otherSubSpecials;

    /**
     * @var Collection|ConditionType[]
     * @MongoDB\ReferenceMany (targetDocument=ConditionType::class, storeAs="id", name="other_sub_conditions")
     */
    protected $otherSubConditions;

    public function __construct()
    {
        $this->otherSubSpecials = new ArrayCollection();
        $this->otherSubConditions = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getSpecialType(): SpecialType
    {
        return $this->specialType;
    }

    public function setSpecialType(SpecialType $specialType): void
    {
        $this->specialType = $specialType;
    }

    public function getOtherSubSpecials()
    {
        return $this->otherSubSpecials;
    }

    public function setOtherSubSpecials($otherSubSpecials): void
    {
        $this->otherSubSpecials = $otherSubSpecials;
    }

    public function addOtherSubSpecial(SpecialType $otherSpecialReference)
    {
        $this->otherSubSpecials->add($otherSpecialReference);
    }

    public function removeOtherSubSpecial(SpecialType $otherSpecialReference)
    {
        $this->otherSubSpecials->removeElement($otherSpecialReference);
    }

    public function getOtherSubConditions()
    {
        return $this->otherSubConditions;
    }

    public function setOtherSubConditions($otherSubConditions): void
    {
        $this->otherSubConditions = $otherSubConditions;
    }

    public function addOtherSubCondition(ConditionType $otherSubCondition)
    {
        $this->otherSubConditions->add($otherSubCondition);
    }

    public function removeOtherSubCondition(ConditionType $otherSubCondition)
    {
        $this->otherSubConditions->removeElement($otherSubCondition);
    }

    public function __toString()
    {
        return SpecialStringMapper::getSpecialAsString($this);
    }


}

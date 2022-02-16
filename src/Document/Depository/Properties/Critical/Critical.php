<?php

declare(strict_types=1);

namespace App\Document\Depository\Properties\Critical;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\ConditionType\ConditionType;
use App\Document\Book\Book;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsInterface;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsTrait;
use App\Document\Common\Extrafield\Extrafield;
use App\Service\RpItemPropertiesAsString\CriticalStringMapper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use App\Document\Depository\Properties\Critical\CriticalType;
use Exception;

/**
 * @MongoDB\EmbeddedDocument()
 */
class Critical
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
     * @var CriticalType
     * @MongoDB\ReferenceOne (targetDocument=CriticalType::class, storeAs="id", name="critical_type")
     */
    protected $criticalType;

    /**
     * @var Collection|CriticalType[]
     * @MongoDB\ReferenceMany (targetDocument=CriticalType::class, storeAs="id", name="other_sub_criticals")
     */
    protected $otherSubCriticals;

    /**
     * @var Collection|ConditionType[]
     * @MongoDB\ReferenceMany (targetDocument=ConditionType::class, storeAs="id", name="other_sub_conditions")
     */
    protected $otherSubConditions;

    public function __construct()
    {
        $this->otherSubCriticals = new ArrayCollection();
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

    public function getCriticalType(): CriticalType
    {
        return $this->criticalType;
    }

    public function setCriticalType(CriticalType $criticalType): void
    {
        $this->criticalType = $criticalType;
    }

    public function getOtherSubCriticals()
    {
        return $this->otherSubCriticals;
    }

    public function setOtherSubCriticals($otherSubCriticals): void
    {
        $this->otherSubCriticals = $otherSubCriticals;
    }

    public function addOtherSubCritical(CriticalType $otherCriticalReference)
    {
        $this->otherSubCriticals->add($otherCriticalReference);
    }

    public function removeOtherSubCritical(CriticalType $otherCriticalReference)
    {
        $this->otherSubCriticals->removeElement($otherCriticalReference);
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
        return CriticalStringMapper::getCriticalAsString($this);
    }
}

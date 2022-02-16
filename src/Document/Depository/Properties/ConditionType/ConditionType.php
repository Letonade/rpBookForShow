<?php

declare(strict_types=1);

namespace App\Document\Depository\Properties\ConditionType;

use App\Document\Depository\Depository;
use App\Document\Common\Customfield\Customfield;
use App\Document\Common\Customfield\DocumentWithCustomfieldsInterface;
use App\Document\Common\Customfield\DocumentWithCustomfieldsTrait;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsInterface;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsTrait;
use App\Document\Common\Extrafield\Extrafield;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\PersistentCollection;

/**
 * @MongoDB\Document(collection="ConditionTypes", repositoryClass="App\Repository\Depository\ConditionTypeRepository")
 */
class ConditionType implements DocumentWithExtrafieldsInterface, DocumentWithCustomfieldsInterface
{
    use DocumentWithExtrafieldsTrait;
    use DocumentWithCustomfieldsTrait;

    public const DOCUMENT_NAME = 'ConditionType';

    /**
     * @var string
     * @MongoDB\Id(type="id")
     */
    protected $id;

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
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $title;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $specification;

    /**
     * @var string|null
     * @MongoDB\Field(type="string")
     */
    protected $description;

    /**
     * @var string|null
     * @MongoDB\Field(type="string", name="short_description")
     */
    protected $shortDescription;

    /**
     * @var Collection|ConditionType[]
     * @MongoDB\ReferenceMany (targetDocument=ConditionType::class, storeAs="id", name="sub_conditions")
     */
    protected $subConditions;

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
        $this->subConditions = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
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

    public function getSpecification(): string
    {
        return $this->specification;
    }

    public function setSpecification(string $specification): void
    {
        $this->specification = $specification;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    public function getSubConditions()
    {
        return $this->subConditions;
    }

    public function setSubConditions($subConditions): void
    {
        $this->subConditions = $subConditions;
    }

    public function addSubCondition(ConditionType $subCondition)
    {
        if (!in_array($subCondition, $this->subConditions->getValues())){
            $this->subConditions->add($subCondition);
        }
        // uncomment if you want to update other side
        //$subCondition->setConditionType($this);
    }

    public function removeSubCondition(ConditionType $subCondition)
    {
        $this->subConditions->removeElement($subCondition);
        // uncomment if you want to update other side
        //$subCondition->setConditionType(null);
    }


}

<?php

declare(strict_types=1);

namespace App\Document\Depository\Properties\WeaponCategory;

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

/**
 * @MongoDB\Document(collection="WeaponCategories", repositoryClass="App\Repository\Depository\WeaponCategoryRepository")
 */
class WeaponCategory implements DocumentWithExtrafieldsInterface, DocumentWithCustomfieldsInterface
{
    use DocumentWithExtrafieldsTrait;
    use DocumentWithCustomfieldsTrait;

    public const DOCUMENT_NAME = 'WeaponCategory';

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
     * @var string|null
     * @MongoDB\Field(type="string")
     */
    protected $description;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }


}

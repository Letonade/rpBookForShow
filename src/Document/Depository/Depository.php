<?php

declare(strict_types=1);

namespace App\Document\Depository;

use App\Document\Common\Extrafield\DocumentWithExtrafieldsInterface;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsTrait;
use App\Document\Common\Extrafield\Extrafield;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="Depository", repositoryClass="App\Repository\Depository\DepositoryRepository")
 */
class Depository implements DocumentWithExtrafieldsInterface
{
    use DocumentWithExtrafieldsTrait;

    public const DOCUMENT_NAME = 'depository';

    /**
     * @var string
     * @MongoDB\Id(type="id")
     */
    protected $id;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $name;

    /**
     * @var string|null
     * @MongoDB\Field(type="string")
     */
    protected $description = '';

    /**
     * @var bool
     * @MongoDB\Field(type="bool")
     */
    protected $available = false;

    /**
     * @var Collection|Extrafield[]
     * @MongoDB\EmbedMany(targetDocument=Extrafield::class)
     */
    protected $extrafields;

    /**
     * @var Collection|Depository[]
     * @MongoDB\EmbedMany(targetDocument=Extrafield::class, name="$parent_depositorys")
     */
    protected $parentDepositorys;

    public function __construct()
    {
        $this->extrafields = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
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

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): void
    {
        $this->available = $available;
    }

    public function getParentDepositorys()
    {
        return $this->parentDepositorys;
    }

    public function setParentDepositorys($parentDepositorys): void
    {
        $this->parentDepositorys = $parentDepositorys;
    }

    public function addParentDepository(Depository $parentDepository)
    {
        $this->parentDepositorys->add($parentDepository);
        // uncomment if you want to update other side
        //$parentDepository->setDepository($this);
    }

    public function removeParentDepository(Depository $parentDepository)
    {
        $this->parentDepositorys->removeElement($parentDepository);
        // uncomment if you want to update other side
        //$parentDepository->setDepository(null);
    }


}

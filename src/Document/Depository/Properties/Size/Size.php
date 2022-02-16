<?php

declare(strict_types=1);

namespace App\Document\Depository\Properties\Size;

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
 * @MongoDB\Document(collection="Sizes", repositoryClass="App\Repository\Depository\SizeRepository")
 */
class Size implements DocumentWithExtrafieldsInterface, DocumentWithCustomfieldsInterface
{
    use DocumentWithExtrafieldsTrait;
    use DocumentWithCustomfieldsTrait;

    public const DOCUMENT_NAME = 'Size';

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
     * @MongoDB\Field(type="string", name="near_height_or_length")
     */
    protected $nearHeightOrLength;

    /**
     * @var string
     * @MongoDB\Field(type="string", name="near_weigth")
     */
    protected $nearWeight;

    /**
     * @var string
     * @MongoDB\Field(type="string", name="near_space")
     */
    protected $nearSpace;

    /**
     * @var string
     * @MongoDB\Field(type="string", name="natural_reach_tall")
     */
    protected $naturalReachTall;

    /**
     * @var string
     * @MongoDB\Field(type="string", name="natural_reach_long")
     */
    protected $naturalReachLong;

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

    public function getNearHeightOrLength(): string
    {
        return $this->nearHeightOrLength;
    }

    public function setNearHeightOrLength(string $nearHeightOrLength): void
    {
        $this->nearHeightOrLength = $nearHeightOrLength;
    }

    public function getNearWeight(): string
    {
        return $this->nearWeight;
    }

    public function setNearWeight(string $nearWeight): void
    {
        $this->nearWeight = $nearWeight;
    }

    public function getNearSpace(): string
    {
        return $this->nearSpace;
    }

    public function setNearSpace(string $nearSpace): void
    {
        $this->nearSpace = $nearSpace;
    }

    public function getNaturalReachTall(): string
    {
        return $this->naturalReachTall;
    }

    public function setNaturalReachTall(string $naturalReachTall): void
    {
        $this->naturalReachTall = $naturalReachTall;
    }

    public function getNaturalReachLong(): string
    {
        return $this->naturalReachLong;
    }

    public function setNaturalReachLong(string $naturalReachLong): void
    {
        $this->naturalReachLong = $naturalReachLong;
    }



}

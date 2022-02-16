<?php

declare(strict_types=1);

namespace App\Document\Depository\Properties\FamilyDescription;

use App\Document\Depository\Depository;
use App\Document\Depository\RpItem\Structure\RpItem;
use App\Document\Book\Book;
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
 * @MongoDB\Document(collection="FamilyDescriptions", repositoryClass="App\Repository\Depository\FamilyDescriptionRepository")
 */
class FamilyDescription implements DocumentWithExtrafieldsInterface, DocumentWithCustomfieldsInterface
{
    use DocumentWithExtrafieldsTrait;
    use DocumentWithCustomfieldsTrait;

    public const DOCUMENT_NAME = 'FamilyDescription';

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
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $name;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $slug;

    /**
     * @var string|null
     * @MongoDB\Field(type="string")
     */
    protected $description;

    /**
     * @var Collection|RpItem[]
     * @MongoDB\ReferenceMany (targetDocument=RpItem::class)
     */
    protected $members;

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
        $this->members = new ArrayCollection();
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function setMembers($members): void
    {
        $this->members = $members;
    }

    public function addMember(RpItem $member)
    {
        if (!$this->getMembers()->contains($member)) {
            $this->members->add($member);
        }
        // uncomment if you want to update other side
        //$member->setFamilyDescription($this);
    }

    public function removeMember(RpItem $member)
    {
        if ($this->getMembers()->contains($member)) {
            $this->members->removeElement($member);
        }
        // uncomment if you want to update other side
        //$member->setFamilyDescription(null);
    }

}

<?php

declare(strict_types=1);

namespace App\Document\Depository\Properties\Critical;

use App\Document\Depository\Depository;
use App\Document\Book\Book;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsInterface;
use App\Document\Common\Extrafield\DocumentWithExtrafieldsTrait;
use App\Document\Common\Extrafield\Extrafield;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Exception;

/**
 * @MongoDB\Document(collection="CriticalTypes", repositoryClass="App\Repository\Depository\CriticalRepository")
 */
class CriticalType implements DocumentWithExtrafieldsInterface
{
    use DocumentWithExtrafieldsTrait;

    public const DOCUMENT_NAME = 'CriticalType';

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
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $label;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $arguments = '';

    /**
     * @var string
     * @MongoDB\Field(type="string", name="start_delimiter")
     */
    protected $startDelimiter;

    /**
     * @var string
     * @MongoDB\Field(type="string", name="end_delimiter")
     */
    protected $endDelimiter;

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
     * @var Collection|CriticalType[]|null
     * @MongoDB\EmbedMany(targetDocument=CriticalType::class, name="sub_criticals")
     */
    protected $subCriticals;

    /**
     * @var Collection|Extrafield[]
     * @MongoDB\EmbedMany(targetDocument=Extrafield::class)
     */
    protected $extrafields;

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
     * @var Depository
     * @MongoDB\ReferenceOne(targetDocument=Depository::class, storeAs="id")
     */
    protected $depository;

    public function __construct()
    {
        $this->extrafields = new ArrayCollection();
        $this->subCriticals = new ArrayCollection();
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

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
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

    public function getStartDelimiter(): string
    {
        return $this->startDelimiter;
    }

    public function getArguments(): string
    {
        return $this->arguments;
    }

    public function setArguments(string $arguments): void
    {
        $this->arguments = $arguments;
    }

    public function setStartDelimiter(string $startDelimiter): void
    {
        $this->startDelimiter = $startDelimiter;
    }

    public function getEndDelimiter(): string
    {
        return $this->endDelimiter;
    }

    public function setEndDelimiter(string $endDelimiter): void
    {
        $this->endDelimiter = $endDelimiter;
    }

    /**
     * @return Collection|CriticalType[]
     */
    public function getSubCriticals(): iterable
    {
        return $this->subCriticals ?? new ArrayCollection();
    }

    public function setSubCriticals(ArrayCollection $subCriticals): void
    {
        $this->subCriticals = $subCriticals;
    }

    public function addSubCritical(CriticalType $subCritical): void
    {
        $this->subCriticals->add($subCritical);
    }

    public function getSubCriticalsById(string $subCriticalId): ?Extrafield
    {
        foreach ($this->subCriticals as $subCritical) {
            if ($subCritical->getId() == $subCriticalId) {
                return $subCritical;
            }
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public function removeSubCritical(string $subCriticalId): void
    {
        foreach ($this->getSubCriticals() as $key => $subCritical) {
            if ($subCriticalId == $subCritical->getId()) {
                $this->getSubCriticals()->remove($key);

                return;
            }
        }

        throw new Exception('Not found sub critical.');
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

    public function getDepository(): Depository
    {
        return $this->depository;
    }

    public function setDepository(Depository $depository): void
    {
        $this->depository = $depository;
    }
}

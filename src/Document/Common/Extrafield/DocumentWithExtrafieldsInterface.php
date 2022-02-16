<?php

declare(strict_types=1);

namespace App\Document\Common\Extrafield;

use Doctrine\Common\Collections\ArrayCollection;

interface DocumentWithExtrafieldsInterface
{
    public function getId();

    public function getExtrafields(): iterable;

    public function setItemExtrafields(ArrayCollection $extrafields): void;

    public function addExtrafield(Extrafield $extrafield): void;

    public function setExtrafield(string $extrafieldId, string $value): void;

    public function getExtrafieldById(string $extrafieldId): ?Extrafield;

    public function getExtrafieldByHandle(string $handle): ?Extrafield;

    public function removeExtrafield(string $extrafieldId): void;

    public function setExtrafieldPublic(string $extrafieldId, bool $public);

    public function setExtrafieldHandle(string $extrafieldId, string $handle);

    public function getExtrafieldDocumentName(): string;
}

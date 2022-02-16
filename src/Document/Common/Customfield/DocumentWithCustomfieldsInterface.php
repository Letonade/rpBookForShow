<?php

declare(strict_types=1);

namespace App\Document\Common\Customfield;

use Doctrine\Common\Collections\ArrayCollection;

interface DocumentWithCustomfieldsInterface
{
    public function getId();

    public function getCustomfields(): iterable;

    public function setCustomfields(ArrayCollection $customfields): void;

    public function addCustomfield(Customfield $customfield): void;

    public function setCustomfield(string $customfieldId, string $value): void;

    public function getCustomfieldById(string $customfieldId): ?Customfield;

    public function getCustomfieldByHandle(string $name): ?Customfield;

    public function removeCustomfield(string $customfieldId): void;

    public function setCustomfieldPublic(string $customfieldId, bool $public);

    public function setCustomfieldHandle(string $customfieldId, string $name);

    public function getCustomfieldDocumentName(): string;
}

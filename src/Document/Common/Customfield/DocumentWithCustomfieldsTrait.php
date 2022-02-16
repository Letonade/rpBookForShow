<?php

declare(strict_types=1);

namespace App\Document\Common\Customfield;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;

trait DocumentWithCustomfieldsTrait
{
    public function getCustomfieldDocumentName(): string
    {
        return self::DOCUMENT_NAME;
    }

    /**
     * @return Collection|Customfield[]
     */
    public function getCustomfields(): iterable
    {
        return $this->customfields ?? new ArrayCollection();
    }

    public function setCustomfields(ArrayCollection $customfield): void
    {
        $this->customfields = $customfield;
    }

    public function addCustomfield(Customfield $customfield): void
    {
        $this->customfields->add($customfield);
    }

    /**
     * @throws Exception
     */
    public function setCustomfield(string $customfieldId, string $value): void
    {
        foreach ($this->getCustomfields() as $currentItemCustomfield) {
            if ($customfieldId !== $currentItemCustomfield->getId()) {
                continue;
            }

            $currentItemCustomfield->setValue($value);

            return;
        }

        $this->throwCustomfieldNotFoundException($customfieldId);
    }

    public function getCustomfieldById(string $customfieldId): ?Customfield
    {
        foreach ($this->customfields as $Customfield) {
            if ($Customfield->getId() == $customfieldId) {
                return $Customfield;
            }
        }

        return null;
    }

    public function getCustomfieldByHandle(string $handle): ?Customfield
    {
        foreach ($this->getCustomfields() as $Customfield) {
            if ($handle !== $Customfield->getHandle()) {
                continue;
            }

            return $Customfield;
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public function removeCustomfield(string $customfieldId): void
    {
        foreach ($this->getCustomfields() as $key => $customfield) {
            if ($customfieldId == $customfield->getId()) {
                $this->getCustomfields()->remove($key);

                return;
            }
        }

        $this->throwCustomfieldNotFoundException($customfieldId);
    }

    /**
     * @throws Exception
     */
    public function setCustomfieldPublic(string $customfieldId, bool $public): void
    {
        foreach ($this->customfields as $customfield) {
            if ($customfieldId == $customfield->getId()) {
                $customfield->setPublic($public);

                return;
            }
        }

        $this->throwCustomfieldNotFoundException($customfieldId);
    }

    public function setItemCustomfieldByHandle(string $handle, string $value): void
    {
        $customfield = $this->getCustomfieldByHandle($handle);

        if (!$customfield instanceof Customfield) {
            $customfield = new Customfield();
            $customfield->setHandle($handle);
            $this->addCustomfield($customfield);
        }

        $customfield->setValue($value);
    }

    /**
     * @throws Exception
     */
    private function throwCustomfieldNotFoundException(string $customfieldId): void
    {
        throw new Exception(sprintf('Customfield with id %s not found in %s %s', $customfieldId,
            $this->getDocumentName(),
            $this->getId()));
    }

    /**
     * @throws Exception
     */
    public function setCustomfieldHandle(string $customfieldId, string $handle): void
    {
        foreach ($this->customfields as $customfield) {
            if ($customfieldId == $customfield->getId()) {
                $customfield->setHandle($handle);

                return;
            }
        }

        $this->throwCustomfieldNotFoundException($customfieldId);
    }
}

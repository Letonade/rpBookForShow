<?php

declare(strict_types=1);

namespace App\Document\Common\Extrafield;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;

trait DocumentWithExtrafieldsTrait
{
    public function getExtrafieldDocumentName(): string
    {
        return self::DOCUMENT_NAME;
    }

    /**
     * @return Collection|Extrafield[]
     */
    public function getExtrafields(): iterable
    {
        return $this->extrafields ?? new ArrayCollection();
    }

    public function setItemExtrafields(ArrayCollection $extrafields): void
    {
        $this->extrafields = $extrafields;
    }

    public function addExtrafield(Extrafield $extrafield): void
    {
        $this->extrafields->add($extrafield);
    }

    /**
     * @throws Exception
     */
    public function setExtrafield(string $extrafieldId, string $value): void
    {
        foreach ($this->getExtrafields() as $currentExtrafield) {
            if ($extrafieldId !== $currentExtrafield->getId()) {
                continue;
            }

            $currentExtrafield->setValue($value);

            return;
        }

        $this->throwExtrafieldNotFoundException($extrafieldId);
    }

    public function getExtrafieldById(string $extrafieldId): ?Extrafield
    {
        foreach ($this->extrafields as $extrafield) {
            if ($extrafield->getId() == $extrafieldId) {
                return $extrafield;
            }
        }

        return null;
    }

    public function getExtrafieldByHandle(string $handle): ?Extrafield
    {
        foreach ($this->getExtrafields() as $extrafield) {
            if ($handle !== $extrafield->getHandle()) {
                continue;
            }

            return $extrafield;
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public function removeExtrafield(string $extrafieldId): void
    {
        foreach ($this->getExtrafields() as $key => $extrafield) {
            if ($extrafieldId == $extrafield->getId()) {
                $this->getExtrafields()->remove($key);

                return;
            }
        }

        $this->throwExtrafieldNotFoundException($extrafieldId);
    }

    /**
     * @throws Exception
     */
    public function setExtrafieldPublic(string $extrafieldId, bool $public): void
    {
        foreach ($this->extrafields as $extrafield) {
            if ($extrafieldId == $extrafield->getId()) {
                $extrafield->setPublic($public);

                return;
            }
        }

        $this->throwExtrafieldNotFoundException($extrafieldId);
    }

    public function setExtrafieldByHandle(string $handle, string $value): void
    {
        $extrafield = $this->getExtrafieldByHandle($handle);

        if (!$extrafield instanceof Extrafield) {
            $extrafield = new Extrafield();
            $extrafield->setHandle($handle);
            $this->addExtrafield($extrafield);
        }

        $extrafield->setValue($value);
    }

    /**
     * @throws Exception
     */
    private function throwExtrafieldNotFoundException(string $extrafieldId): void
    {
        throw new Exception(sprintf('Extrafield with id %s not found in %s %s', $extrafieldId, $this->getDocumentName(),
            $this->getId()));
    }

    /**
     * @throws Exception
     */
    public function setExtrafieldHandle(string $extrafieldId, string $handle): void
    {
        foreach ($this->extrafields as $extrafield) {
            if ($extrafieldId == $extrafield->getId()) {
                $extrafield->setHandle($handle);

                return;
            }
        }

        $this->throwExtrafieldNotFoundException($extrafieldId);
    }
}

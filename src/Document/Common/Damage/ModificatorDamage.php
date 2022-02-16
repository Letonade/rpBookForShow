<?php
/**
 * @author Letonade
 * @date   18/11/2021 18:33
 */

namespace App\Document\Common\Damage;


use App\Document\Depository\Properties\DamageType\DamageType;
use App\Service\RpItemPropertiesAsString\ModificatorDamageStringMapper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument()
 */
class ModificatorDamage
{
    /**
     * @var bool
     * @MongoDB\Field(type="bool", name="is_additional_damage")
     */
    protected $isAdditionalDamage;

    /**
     * @var int
     * @MongoDB\Field(type="int", name="damage_dice_amount")
     */
    protected $damageDiceAmount;

    /**
     * @var int
     * @MongoDB\Field(type="int", name="damage_dice_face_count")
     */
    protected $damageDiceFaceCount;

    /**
     * @var int
     * @MongoDB\Field(type="int", name="damage_fixed_amount")
     */
    protected $damageFixedAmount;

    /**
     * @var Collection|DamageType[]
     * @MongoDB\ReferenceMany(targetDocument=DamageType::class, storeAs="id", name="damage_types")
     */
    protected $damageTypes;

    public function __construct()
    {
        $this->damageTypes = new ArrayCollection();
    }

    public function isAdditionalDamage(): bool
    {
        return $this->isAdditionalDamage;
    }

    public function setIsAdditionalDamage(bool $isAdditionalDamage): void
    {
        $this->isAdditionalDamage = $isAdditionalDamage;
    }

    public function getDamageDiceAmount(): int
    {
        return $this->damageDiceAmount;
    }

    public function setDamageDiceAmount(int $damageDiceAmount): void
    {
        $this->damageDiceAmount = $damageDiceAmount;
    }

    public function getDamageDiceFaceCount(): int
    {
        return $this->damageDiceFaceCount;
    }

    public function setDamageDiceFaceCount(int $damageDiceFaceCount): void
    {
        $this->damageDiceFaceCount = $damageDiceFaceCount;
    }

    public function getDamageFixedAmount(): int
    {
        return $this->damageFixedAmount;
    }

    public function setDamageFixedAmount(int $damageFixedAmount): void
    {
        $this->damageFixedAmount = $damageFixedAmount;
    }

    public function getDamageTypes()
    {
        return $this->damageTypes;
    }

    public function setDamageTypes($damageTypes): void
    {
        $this->damageTypes = $damageTypes;
    }

    public function addDamageType(DamageType $damageType)
    {
        $this->damageTypes[] = $damageType;
    }

    public function removeDamageType(DamageType $damageType)
    {
        if (false !== $key = array_search($damageType, $this->damageTypes, true)) {
            array_splice($this->damageTypes, $key, 1);
        }
    }

    public function  __toString()
    {
        return ModificatorDamageStringMapper::getModificatorDamageAsString($this);
    }
}

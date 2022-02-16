<?php

declare(strict_types=1);

namespace App\Repository\Depository;

use App\Document\Depository\RpItem;
use App\Document\Depository\RpItem\Structure\DiscriminatoryRpItem;
use App\Form\FrontTwig\Depository\FilterData\FrontTwigDepositoryRpItemFilterData;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Exception;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;
use MongoRegex;
use App\Document\Depository\RpItem\Weapon\SniperWeaponRpItem;
use MongoDB\Collection;
use function PHPUnit\Framework\isEmpty;

class RpItemRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscriminatoryRpItem::class);
    }

    public function findCaseInsentive(string $field, string $search)
    {
        $result = $this->createQueryBuilder($this->documentName)
            ->field($field)
            ->equals($search)
            ->getQuery(['collation' => ['locale' => 'en', 'strength' => 2]])
            ->execute()
            ->toArray();
        if (!isset($result[0])) {
            return null;
        }

        return $result[0];
    }

    // Link youtube for filter watch?v=4uYpFjfUUbc, thank you Grafikart.fr
    public function findWithFilterForm(string $type, array $sorterFields = [], FrontTwigDepositoryRpItemFilterData $filter)
    {
        if (!in_array($type, DiscriminatoryRpItem::DISCRIMINATOR_MAP)) {
            throw new Exception('[findWithFilterForm] Not a valid discriminator type:' . $type);
        }

        $query = $this->createQueryBuilder('rpItem')
            ->field('type')->equals($type)
            ->sort($sorterFields);

        if ($filter->depositorys && !$filter->depositorys->isEmpty()) {
            $objectIdDepositorys = array_map(function ($depositoryObject){return new ObjectId($depositoryObject->getId());} ,$filter->depositorys->toArray());
            $query->field('depository')->in($objectIdDepositorys);
        }
        if ($filter->name) {
            $query->field('name')->equals(new Regex($filter->name, 'i'));
        }
        //Here we use DocumentType selector instead of a classic ChoiceType
        if ($filter->techTypes && !$filter->techTypes->isEmpty()) {
            $objectIdTechTypes = array_map(function ($techTypeObject){return new ObjectId($techTypeObject->getId());} ,$filter->techTypes->toArray());
            $query->field('tech_type')->in($objectIdTechTypes);
        }
        //Here we use ChoiceType selector instead of a previously used DocumentType (entity is for mysql)
        if ($filter->sourceBooks) {
            $objectIdSourceBooks = array_map(function (string $bookId){return new ObjectId($bookId);} ,$filter->sourceBooks);
            $query->field('source_book')->in($objectIdSourceBooks);
        }
        if ($filter->minCost) {
            $query->field('cost.value')->gte($filter->minCost);
        }
        if ($filter->minLevel) {
            $query->field('level')->gte($filter->minLevel);
        }
        if ($filter->maxLevel) {
            $query->field('level')->lte($filter->maxLevel);
        }

        if ($filter->damageTypes && $filter->damageTypesOr) { // To test on weapons
            $damTypeInnerExpr = $query->expr();
            foreach ($filter->damageTypes as $damageType) {
                $damTypeInnerExpr
                    ->addOr($query->expr()->field('damages.damage_types')->equals(new ObjectId($damageType)));
            }
            $query // same as 'WHERE... AND (x==a OR y==a)
            ->addAnd($damTypeInnerExpr);
        } elseif ($filter->damageTypes && !$filter->damageTypesOr) {
            foreach ($filter->damageTypes as $damageType) {
                $query
                    ->addAnd($query->expr()
                        ->addOr($query->expr()->field('damages.damage_types')->equals(new ObjectId($damageType)))
                    );
            }
        }

        if ($filter->minRawDamage) { // To test on weapons
            $query->field('extrafields.handle')->equals('rawMinimumDamage');
        }
        if ($filter->maxRawDamage) { // To test on weapons
            $query->field('extrafields.handle')->equals('rawMaximumDamage');
        }

        if ($filter->specialTypes && $filter->specialTypesOr) { // make it search in sub too
            $specialTypeInnerExpr = $query->expr();
            foreach ($filter->specialTypes as $specialType) {
                $specialTypeInnerExpr
                    ->addOr($query->expr()->field('specials.special_type')->equals(new ObjectId($specialType)))
                    ->addOr($query->expr()->field('specials.other_sub_specials')->equals(new ObjectId($specialType)));
            }
            $query->addAnd($specialTypeInnerExpr);
        } elseif ($filter->specialTypes && !$filter->specialTypesOr) {
            foreach ($filter->specialTypes as $specialType) {
                $query // same as 'WHERE... AND (x==a OR y==a)
                ->addAnd($query->expr()
                    ->addOr($query->expr()->field('specials.special_type')->equals(new ObjectId($specialType)))
                    ->addOr($query->expr()->field('specials.other_sub_specials')->equals(new ObjectId($specialType)))
                );
            }
        }

        if ($filter->criticalTypes && $filter->criticalTypesOr) { // To test on weapons, make it search in sub too
            $criticalInnerExpr = $query->expr();
            foreach ($filter->criticalTypes as $criticalType) {
                $criticalInnerExpr
                    ->addOr($query->expr()->field('criticals.critical_type')->equals(new ObjectId($criticalType)))
                    ->addOr($query->expr()->field('criticals.other_sub_criticals')->equals(new ObjectId($criticalType)));
            }
            $query->addAnd($criticalInnerExpr);
        } elseif ($filter->criticalTypes && !$filter->criticalTypesOr) {
            foreach ($filter->criticalTypes as $criticalType) {
                $query
                    ->addAnd($query->expr()
                        ->addOr($query->expr()->field('criticals.critical_type')->equals(new ObjectId($criticalType)))
                        ->addOr($query->expr()->field('criticals.other_sub_criticals')->equals(new ObjectId($criticalType)))
                    );
            }
        }

        $results = $query->getQuery()->toArray();

        if ($filter->minRawDamage) {
            $rawDam = 0;
            foreach ($results as $key => $result) {
                if (!$result->getExtrafieldByHandle('rawMinimumDamage') || !$result->getExtrafieldByHandle('rawMinimumDamage')->getValue()) {
                    array_splice($results, array_search($result, $results), 1);
                    dump($key);
                    continue;
                }
                $rawDam = $result->getExtrafieldByHandle('rawMinimumDamage')->getValue();
                if ((int)$rawDam < (int)$filter->minRawDamage){
                    array_splice($results, array_search($result, $results), 1);
                    continue;
                }
            }
        }
        if ($filter->maxRawDamage) {
            $rawDam = 0;
            foreach ($results as $key => $result) {
                if (!$result->getExtrafieldByHandle('rawMaximumDamage') || !$result->getExtrafieldByHandle('rawMaximumDamage')->getValue()) {
                    array_splice($results, array_search($result, $results), 1);
                    continue;
                }
                $rawDam = $result->getExtrafieldByHandle('rawMaximumDamage')->getValue();
                if ((int)$rawDam < (int)$filter->maxRawDamage){
                    array_splice($results, array_search($result, $results), 1);
                    continue;
                }
            }
        }

        return $results;
    }
}


// Notabene:
// $query // same as 'WHERE... AND (x==a OR y==a)
// ->addAnd($query->expr()
// ->addOr($query->expr()->field('specials.special_type')->equals(new ObjectId($specialType)))
// ->addOr($query->expr()->field('specials.other_sub_specials')->equals(new ObjectId($specialType)))
// );

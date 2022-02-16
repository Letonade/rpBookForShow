<?php

declare(strict_types=1);

namespace App\Form\FrontTwig\Depository;

use App\Document\Depository\Depository;
use App\Document\Depository\Properties\Critical\CriticalType;
use App\Document\Depository\Properties\DamageType\DamageType;
use App\Document\Depository\Properties\Special\SpecialType;
use App\Document\Depository\Properties\TechType\TechType;
use App\Document\Depository\RpItem\Structure\RpItem;
use App\Document\Book\Book;
use App\Form\FrontTwig\Depository\FilterData\FrontTwigDepositoryRpItemFilterData;
use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceFilter;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FrontTwigDepositoryRpItemFilterForm extends AbstractType
{
    /**
     * @var DocumentManager
     */
    private $documentManager;
    private $bookChoices;
    private $damageTypeChoices;
    private $specialTypesChoices;
    private $criticalTypesChoices;


    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->initializeChoices();
        $builder
            ->add('name', TextType::class, [
                'label'    => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Name',
                ],
            ])
            ->add('depositorys', DocumentType::class, [
                'class'        => Depository::class,
                'choice_label' => 'name',
                'multiple'     => true,
                'label'        => false,
                'required'     => false,
            ])
            ->add('techTypes', DocumentType::class, [
                'class'        => TechType::class,
                'choice_label' => 'name',
                'multiple'     => true,
                'label'        => false,
                'required'     => false,
            ])
            ->add('sourceBooks', ChoiceType::class, [
                'choices'  => $this->bookChoices,
                'multiple' => true,
                'label'    => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Books',
                ],
            ])
            ->add('minCost', NumberType::class, [
                'label'    => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => '-Cost',
                ],
            ])
            ->add('maxCost', NumberType::class, [
                'label'    => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => '+Cost',
                ],
            ])
            ->add('minLevel', NumberType::class, [
                'label'    => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => '-Lvl',
                ],
            ])
            ->add('maxLevel', NumberType::class, [
                'label'    => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => '+Lvl',
                ],
            ])
            ->add('damageTypes', ChoiceType::class, [
                'choices'  => $this->damageTypeChoices,
                'label'    => false,
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('damageTypesOr', CheckboxType::class, [
                'label'    => 'OR - Damages Types',
                'required' => false,
            ])
            ->add('minRawDamage', NumberType::class, [
                'label'    => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Min. Raw Damage',
                ],
            ])
            ->add('maxRawDamage', NumberType::class, [
                'label'    => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Max. Raw Damage',
                ],
            ])
            ->add('specialTypes', ChoiceType::class, [
                'choices'  => $this->specialTypesChoices,
                'label'    => false,
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('specialTypesOr', CheckboxType::class, [
                'label'    => 'OR - Specials Types',
                'required' => false,
            ])
            ->add('criticalTypes', ChoiceType::class, [
                'choices'  => $this->criticalTypesChoices,
                'label'    => false,
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('criticalTypesOr', CheckboxType::class, [
                'label'    => 'OR - Criticals Types',
                'required' => false,
            ]);
    }

    private function initializeChoices()
    {
        // A tester : entity type witl choice_label and class


        // Books
        $books = $this->documentManager->getRepository(Book::class)->findBy([], ['date' => 'ASC']);
        $booksId = array_map(function (Book $book) {
            return $book->getId();
        }, $books);
        $booksTitle = array_map(function (Book $book) {
            return ' (' . $book->getAcronym() . ') ' . $book->getTitle();
        }, $books);
        // $this->bookChoices = array_merge(['All' => ''], array_combine($booksTitle, $booksId));
        $this->bookChoices = array_merge(array_combine($booksTitle, $booksId));
        //Damage types
        $damageTypes = $this->documentManager->getRepository(DamageType::class)->findBy([], [
            'isKinetic' => 'DESC',
            'isEnergy'  => 'DESC',
            'name'      => 'ASC',
        ]);
        $damageTypesName = array_map(function (DamageType $damageType) {
            return ' (' . $damageType->getAcronym() . ') ' . $damageType->getName();
        }, $damageTypes);
        $damageTypesId = array_map(function (DamageType $damageType) {
            return $damageType->getId();
        }, $damageTypes);
        $this->damageTypeChoices = array_merge(array_combine($damageTypesName, $damageTypesId));
        // Specials
        $specialTypes = $this->documentManager->getRepository(SpecialType::class)->findBy([], [
            'name' => 'ASC',
        ]);
        $specialTypesName = array_map(function (SpecialType $specialType) {
            return $specialType->getName();
        }, $specialTypes);
        $specialTypesId = array_map(function (SpecialType $specialType) {
            return $specialType->getId();
        }, $specialTypes);
        $this->specialTypesChoices = array_merge(array_combine($specialTypesName, $specialTypesId));
        // Criticals
        $criticalTypes = $this->documentManager->getRepository(CriticalType::class)->findBy([], [
            'name' => 'ASC',
        ]);
        $criticalTypesName = array_map(function (CriticalType $criticalType) {
            return $criticalType->getName();
        }, $criticalTypes);
        $criticalTypesId = array_map(function (CriticalType $criticalType) {
            return $criticalType->getId();
        }, $criticalTypes);
        $this->criticalTypesChoices = array_merge(array_combine($criticalTypesName, $criticalTypesId));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => FrontTwigDepositoryRpItemFilterData::class,
            'method'          => 'GET',
            'csrf_protection' => false,
        ]);
    }
}

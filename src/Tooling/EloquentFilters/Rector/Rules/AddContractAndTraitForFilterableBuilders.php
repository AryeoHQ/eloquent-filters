<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\Rector\Rules;

use Illuminate\Database\Eloquent\Builder;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Support\Database\Eloquent\Attributes\Filter;
use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\HasFilters;
use Throwable;

class AddContractAndTraitForFilterableBuilders extends AbstractRector
{
    public function __construct(
        private UseNodesToAddCollector $useNodesToAddCollector
    ) {}

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): null|Node
    {
        if (! $node instanceof Class_) {
            return null;
        }

        // Only process classes that extend Builder
        if ($node->extends?->toString() !== Builder::class && $node->extends?->toString() !== 'Builder') {
            return null;
        }

        $hasChanges = false;
        $hasFilterAttributes = $this->hasFilterAttributes($node);
        $implementsFilterable = $this->implementsFilterableContract($node);
        $usesHasFilters = $this->usesHasFiltersTrait($node);

        // Rule 1: If Filter attribute is used, add both contract and trait if missing
        if ($hasFilterAttributes) {
            if (! $implementsFilterable) {
                $node = $this->addFilterableContract($node);
                $hasChanges = true;
            }
            if (! $usesHasFilters) {
                $node = $this->addHasFiltersTrait($node);
                $hasChanges = true;
            }
        }

        // Rule 2: If HasFilters trait is used, add Filterable contract if missing
        if ($usesHasFilters && ! $implementsFilterable) {
            $node = $this->addFilterableContract($node);
            $hasChanges = true;
        }

        // Rule 3: If Filterable contract is implemented, add HasFilters trait if missing
        if ($implementsFilterable && ! $usesHasFilters) {
            $node = $this->addHasFiltersTrait($node);
            $hasChanges = true;
        }

        return $hasChanges ? $node : null;
    }

    public function hasFilterAttributes(Class_ $node): bool
    {
        if ($node->stmts === []) {
            return false;
        }

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Node\Stmt\ClassMethod && $this->methodHasFilterAttribute($stmt)) {
                return true;
            }
        }

        return false;
    }

    public function methodHasFilterAttribute(Node\Stmt\ClassMethod $method): bool
    {
        if ($method->attrGroups === []) {
            return false;
        }

        foreach ($method->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if ($attr->name instanceof FullyQualified && $attr->name->toString() === Filter::class) {
                    return true;
                }

                if ($attr->name->toString() === 'Filter') {
                    return true;
                }
            }
        }

        return false;
    }

    public function implementsFilterableContract(Class_ $node): bool
    {
        if ($node->implements === []) {
            return false;
        }

        foreach ($node->implements as $interface) {
            if ($interface instanceof FullyQualified && $interface->toString() === Filterable::class) {
                return true;
            }

            if ($interface->toString() === 'Filterable') {
                return true;
            }
        }

        return false;
    }

    public function usesHasFiltersTrait(Class_ $node): bool
    {
        if ($node->stmts === []) {
            return false;
        }

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof TraitUse) {
                foreach ($stmt->traits as $trait) {
                    if ($trait instanceof FullyQualified && $trait->toString() === HasFilters::class) {
                        return true;
                    }

                    if ($trait->toString() === 'HasFilters') {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private function addFilterableContract(Class_ $node): Class_
    {
        // Check if contract is already implemented
        if ($this->implementsFilterableContract($node)) {
            return $node;
        }

        // Add use statement for Filterable contract
        // Only add use import if we have a current file context (not in tests)
        try {
            $this->useNodesToAddCollector->addUseImport(
                new FullyQualifiedObjectType(Filterable::class)
            );
        } catch (Throwable $e) {
            // In test environments, UseNodesToAddCollector might not have a current file
            // This is expected and we can continue without adding the use statement
        }

        $filterableInterface = new Name('Filterable');

        if ($node->implements === []) {
            $node->implements = [$filterableInterface];
        } else {
            $node->implements[] = $filterableInterface;
        }

        return $node;
    }

    private function addHasFiltersTrait(Class_ $node): Class_
    {
        // Check if trait is already used
        if ($this->usesHasFiltersTrait($node)) {
            return $node;
        }

        // Add use statement for HasFilters trait
        // Only add use import if we have a current file context (not in tests)
        try {
            $this->useNodesToAddCollector->addUseImport(
                new FullyQualifiedObjectType(HasFilters::class)
            );
        } catch (Throwable $e) {
            // In test environments, UseNodesToAddCollector might not have a current file
            // This is expected and we can continue without adding the use statement
        }

        $HasFiltersTrait = new Name('HasFilters');
        $traitUse = new TraitUse([$HasFiltersTrait]);

        // Add the trait use at the beginning of the class body
        if ($node->stmts === []) {
            $node->stmts = [$traitUse];
        } else {
            array_unshift($node->stmts, $traitUse);
        }

        return $node;
    }
}

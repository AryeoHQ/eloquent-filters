<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\PHPStan\Rules;

use Illuminate\Database\Eloquent\Builder;
use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Support\Database\Eloquent\Attributes\Filter;
use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\HasFilters;

/**
 * @implements Rule<Class_>
 */
final class FilteringRule implements Rule
{
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param  Class_  $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $errors = [];

        // Only check classes that extend Builder
        if ($node->extends?->toString() !== Builder::class) {
            return $errors;
        }

        if ($this->hasFilterAttributes($node) === false) {
            return $errors;
        }

        // If class has Filter attributes, it must use the trait and implement the contract
        if ($this->usesHasFiltersTrait($node) === false) {
            $errors[] = RuleErrorBuilder::message('Classes with Filter attributes must use the Support\Database\Eloquent\HasFilters trait.')
                ->line($node->getStartLine())
                ->identifier('filtering.attributes.trait')
                ->build();
        }

        if ($this->implementsFilterableContract($node) === false) {
            $errors[] = RuleErrorBuilder::message('Classes with Filter attributes must implement the Support\Database\Eloquent\Contracts\Filterable contract.')
                ->line($node->getStartLine())
                ->identifier('filtering.attributes.contract')
                ->build();
        }

        return $errors;
    }

    private function implementsFilterableContract(Class_ $node): bool
    {
        if ($node->implements === []) {
            return false;
        }

        foreach ($node->implements as $interface) {
            if ($interface instanceof FullyQualified) {
                if ($interface->toString() === Filterable::class) {
                    return true;
                }
            }
        }

        return false;
    }

    private function usesHasFiltersTrait(Class_ $node): bool
    {
        if ($node->stmts === []) {
            return false;
        }

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Node\Stmt\TraitUse) {
                foreach ($stmt->traits as $trait) {
                    if ($trait instanceof FullyQualified) {
                        if ($trait->toString() === HasFilters::class) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    private function hasFilterAttributes(Class_ $node): bool
    {
        if ($node->stmts === []) {
            return false;
        }

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Node\Stmt\ClassMethod) {
                if ($this->methodHasFilterAttribute($stmt)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function methodHasFilterAttribute(Node\Stmt\ClassMethod $method): bool
    {
        if ($method->attrGroups === []) {
            return false;
        }

        foreach ($method->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if ($attr->name instanceof FullyQualified) {
                    if ($attr->name->toString() === Filter::class) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}

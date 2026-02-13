<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\PhpStanTemp\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PhpParser\Node\Stmt\Class_;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;
use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\HasFilters;
use Support\Database\Eloquent\Attributes\Filter;
use Support\Database\Eloquent\Contracts\Filterable;

/**
 * @implements Rule<Class_>
 */
#[NodeType(Class_::class)]
final class FilteringRule extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $node->extends?->toString() === Builder::class;
    }

    public function handle(Node $node, Scope $scope): void
    {
        // If class has Filter attributes, it must use the trait and implement the contract
        if (! $this->inheritsDirectly($node, [HasFilters::class])) {
            $this->error(
                message: 'Classes with Filter attributes must use the Support\Database\Eloquent\HasFilters trait.',
                line: $node->name->getStartLine(),
                identifier: 'filtering.attributes.trait'
            );
        }

        if (! $this->implementsDirectly($node, [Filterable::class])) {
            $this->error(
                message: 'Classes with Filter attributes must implement the Support\Database\Eloquent\Contracts\Filterable contract.',
                line: $node->name->getStartLine(),
                identifier: 'filtering.attributes.contract'
            );
        }
    }
}

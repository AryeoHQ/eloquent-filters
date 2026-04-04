<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\PhpStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\HasFilters;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class FilterableMustUseHasFilters extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inherits($node, Filterable::class)
            && $this->doesNotInherit($node, HasFilters::class);
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: 'Filterable must use HasFilters.',
            line: $node->name->getStartLine(),
            identifier: 'filtering.filterable.mustUse.hasFilters'
        );
    }
}

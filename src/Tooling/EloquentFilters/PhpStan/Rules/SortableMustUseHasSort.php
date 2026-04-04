<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\PhpStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use Support\Database\Eloquent\Contracts\Sortable;
use Support\Database\Eloquent\HasSort;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class SortableMustUseHasSort extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inherits($node, Sortable::class)
            && $this->doesNotInherit($node, HasSort::class);
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: 'Sortable must use HasSort.',
            line: $node->name->getStartLine(),
            identifier: 'sorting.sortable.mustUse.hasSort'
        );
    }
}

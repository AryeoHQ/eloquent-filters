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
final class HasSortMustImplementSortable extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inherits($node, HasSort::class)
            && $this->doesNotInherit($node, Sortable::class);
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: 'HasSort must implement Sortable.',
            line: $node->name->getStartLine(),
            identifier: 'sorting.hasSort.mustImplement.sortable'
        );
    }
}

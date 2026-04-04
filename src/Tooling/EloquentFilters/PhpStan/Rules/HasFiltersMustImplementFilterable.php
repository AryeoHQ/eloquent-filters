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
final class HasFiltersMustImplementFilterable extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inherits($node, HasFilters::class)
            && $this->doesNotInherit($node, Filterable::class);
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: 'HasFilters must implement Filterable.',
            line: $node->name->getStartLine(),
            identifier: 'filtering.hasFilters.mustImplement.filterable'
        );
    }
}

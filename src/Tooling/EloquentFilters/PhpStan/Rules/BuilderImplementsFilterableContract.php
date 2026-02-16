<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\PhpStan\Rules;

use Illuminate\Database\Eloquent\Builder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use Support\Database\Eloquent\Contracts\Filterable;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class BuilderImplementsFilterableContract extends Rule
{
    public function __construct(
        public readonly ReflectionProvider $reflectionProvider,
    ) {}

    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inherits($node, Builder::class, $this->reflectionProvider);
    }

    public function handle(Node $node, Scope $scope): void
    {
        if (! $this->inherits($node, Filterable::class, $this->reflectionProvider)) {
            $this->error(
                message: 'Classes with Filter attributes must implement the Support\Database\Eloquent\Contracts\Filterable contract.',
                line: $node->name->getStartLine(),
                identifier: 'filtering.attributes.contract'
            );
        }
    }
}

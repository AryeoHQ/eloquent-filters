<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\PhpStan\Rules;

use Illuminate\Database\Eloquent\Builder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use Support\Database\Eloquent\HasFilters;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class BuilderUsesHasFiltersTrait extends Rule
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
        if (! $this->inherits($node, HasFilters::class, $this->reflectionProvider)) {
            $this->error(
                message: 'Classes with Filter attributes must use the Support\Database\Eloquent\HasFilters trait.',
                line: $node->name->getStartLine(),
                identifier: 'filtering.attributes.trait'
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\PhpStan\Rules;

use Illuminate\Database\Eloquent\Builder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use Support\Database\Eloquent\HasFilters;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class BuilderUsesHasFiltersTrait extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $node->extends?->toString() === Builder::class;
    }

    public function handle(Node $node, Scope $scope): void
    {
        if (! $this->inheritsDirectly($node, [HasFilters::class])) {
            $this->error(
                message: 'Classes with Filter attributes must use the Support\Database\Eloquent\HasFilters trait.',
                line: $node->name->getStartLine(),
                identifier: 'filtering.attributes.trait'
            );
        }
    }
}

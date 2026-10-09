<?php

declare(strict_types=1);

namespace Tooling\Search\PhpStan\Scout;

use Laravel\Scout\Searchable as ScoutSearchable;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use Support\Search\Scout\Provides\InteractsWithSearchEngine;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class ScoutSearchableMustUseInteractsWithSearchEngine extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inherits($node, ScoutSearchable::class) && $this->doesNotInherit($node, InteractsWithSearchEngine::class);
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: ScoutSearchable::class.' must be used through '.class_basename(InteractsWithSearchEngine::class).'.',
            line: $node->name?->getStartLine() ?? $node->getStartLine(),
            identifier: 'Model.ScoutSearchable.directUsageNotAllowed'
        );
    }
}

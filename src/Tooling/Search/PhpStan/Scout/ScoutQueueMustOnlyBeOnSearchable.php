<?php

declare(strict_types=1);

namespace Tooling\Search\PhpStan\Scout;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use Support\Search\Scout\Attributes\ScoutQueue;
use Support\Search\Scout\Contracts\Searchable;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class ScoutQueueMustOnlyBeOnSearchable extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->hasAttribute($node, ScoutQueue::class) && $this->doesNotInherit($node, Searchable::class);
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: class_basename(ScoutQueue::class).' must only be on '.class_basename(Searchable::class).'.',
            line: $node->name?->getStartLine() ?? $node->getStartLine(),
            identifier: 'ScoutQueue.Searchable.required'
        );
    }
}

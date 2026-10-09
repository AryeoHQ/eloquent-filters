<?php

declare(strict_types=1);

namespace Tooling\Search\PhpStan\Scout;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use Support\Search\Scout\Contracts\Searchable;
use Support\Search\Scout\Provides\InteractsWithSearchEngine;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class SearchableMustUseInteractsWithSearchEngine extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->inherits($node, Searchable::class) && $this->doesNotInherit($node, InteractsWithSearchEngine::class);
    }

    public function handle(Node $node, Scope $scope): void
    {
        $this->error(
            message: class_basename(Searchable::class).' must use '.class_basename(InteractsWithSearchEngine::class).'.',
            line: $node->name?->getStartLine() ?? $node->getStartLine(),
            identifier: 'Searchable.InteractsWithSearchEngine.required'
        );
    }
}

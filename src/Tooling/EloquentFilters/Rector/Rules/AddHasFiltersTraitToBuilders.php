<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\Rector\Rules;

use Illuminate\Database\Eloquent\Builder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Support\Database\Eloquent\HasFilters;
use Tooling\Rector\Rules\Definitions\Attributes\Definition;
use Tooling\Rector\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[Definition('Add the HasFilters trait to the eloquent builder class')]
#[NodeType(Class_::class)]
final class AddHasFiltersTraitToBuilders extends Rule
{
    public function shouldHandle(Node $node): bool
    {
        return $node->extends?->toString() === Builder::class
            || $node->extends?->toString() === 'Builder';
    }

    public function handle(Node $node): Node
    {
        $node = $this->ensureTraitIsUsed($node, HasFilters::class);

        return $node;
    }
}
